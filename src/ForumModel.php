<?php

namespace App;

use PDO;

/**
 * 论坛模型类
 * 处理论坛相关的数据库操作
 */
class ForumModel
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // ==================== 帖子相关操作 ====================

    /**
     * 获取帖子列表
     */
    public function getPosts(int $page = 1, int $limit = 20, ?string $category = null): array
    {
        $offset = ($page - 1) * $limit;

        $sql = "SELECT p.*, u.username, u.avatar,
                (SELECT COUNT(*) FROM forum_replies r WHERE r.post_id = p.id) as reply_count
                FROM forum_posts p
                LEFT JOIN users u ON p.user_id = u.id
                WHERE 1=1";

        $params = [];

        if ($category && $category !== 'all') {
            $sql .= " AND p.category = :category";
            $params[':category'] = $category;
        }

        $sql .= " ORDER BY p.is_pinned DESC, p.updated_at DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * 获取帖子总数
     */
    public function getPostsCount(?string $category = null): int
    {
        $sql = "SELECT COUNT(*) as total FROM forum_posts WHERE 1=1";
        $params = [];

        if ($category && $category !== 'all') {
            $sql .= " AND category = :category";
            $params[':category'] = $category;
        }

        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        $result = $stmt->fetch();

        return (int) $result['total'];
    }

    /**
     * 根据 ID 获取帖子详情
     */
    public function getPostById(int $postId): ?array
    {
        $sql = "SELECT p.*, u.username, u.avatar, u.bio
                FROM forum_posts p
                LEFT JOIN users u ON p.user_id = u.id
                WHERE p.id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $postId, PDO::PARAM_INT);
        $stmt->execute();

        $post = $stmt->fetch();

        if ($post) {
            // 增加浏览次数
            $this->incrementPostViews($postId);
        }

        return $post ?: null;
    }

    /**
     * 创建新帖子
     */
    public function createPost(int $userId, string $title, string $content, string $category = 'general'): int
    {
        $sql = "INSERT INTO forum_posts (user_id, title, content, category)
                VALUES (:user_id, :title, :content, :category)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':content', $content);
        $stmt->bindValue(':category', $category);
        $stmt->execute();

        return (int) $this->db->lastInsertId();
    }

    /**
     * 更新帖子
     */
    public function updatePost(int $postId, string $title, string $content, string $category): bool
    {
        $sql = "UPDATE forum_posts
                SET title = :title, content = :content, category = :category
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':content', $content);
        $stmt->bindValue(':category', $category);
        $stmt->bindValue(':id', $postId, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * 删除帖子
     */
    public function deletePost(int $postId): bool
    {
        $sql = "DELETE FROM forum_posts WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $postId, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * 增加帖子浏览次数
     */
    public function incrementPostViews(int $postId): bool
    {
        $sql = "UPDATE forum_posts SET views = views + 1 WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $postId, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // ==================== 回复相关操作 ====================

    /**
     * 获取帖子的回复列表
     */
    public function getRepliesByPostId(int $postId, int $page = 1, int $limit = 20, ?int $currentUserId = null): array
    {
        $offset = ($page - 1) * $limit;

        $sql = "SELECT r.*, u.username, u.avatar,
                (SELECT COUNT(*) FROM reply_likes rl WHERE rl.reply_id = r.id) as like_count";

        if ($currentUserId !== null) {
            $sql .= ", (SELECT COUNT(*) FROM reply_likes rl WHERE rl.reply_id = r.id AND rl.user_id = :current_user_id) as has_liked";
        }

        $sql .= " FROM forum_replies r
                LEFT JOIN users u ON r.user_id = u.id
                WHERE r.post_id = :post_id
                ORDER BY r.created_at ASC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':post_id', $postId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        if ($currentUserId !== null) {
            $stmt->bindValue(':current_user_id', $currentUserId, PDO::PARAM_INT);
        }

        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * 获取回复总数
     */
    public function getRepliesCount(int $postId): int
    {
        $sql = "SELECT COUNT(*) as total FROM forum_replies WHERE post_id = :post_id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':post_id', $postId, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch();

        return (int) $result['total'];
    }

    /**
     * 创建回复
     */
    public function createReply(int $postId, int $userId, string $content, ?int $parentReplyId = null): int
    {
        $sql = "INSERT INTO forum_replies (post_id, user_id, content, parent_reply_id)
                VALUES (:post_id, :user_id, :content, :parent_reply_id)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':post_id', $postId, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':content', $content);
        $stmt->bindValue(':parent_reply_id', $parentReplyId, PDO::PARAM_NULL);
        $stmt->execute();

        // 更新帖子的回复计数
        $this->updatePostReplyCount($postId);

        return (int) $this->db->lastInsertId();
    }

    /**
     * 更新帖子回复计数
     */
    private function updatePostReplyCount(int $postId): bool
    {
        $sql = "UPDATE forum_posts p
                SET reply_count = (SELECT COUNT(*) FROM forum_replies r WHERE r.post_id = p.id)
                WHERE p.id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $postId, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * 删除回复
     */
    public function deleteReply(int $replyId): bool
    {
        $sql = "DELETE FROM forum_replies WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $replyId, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // ==================== 点赞相关操作 ====================

    /**
     * 切换帖子点赞状态
     */
    public function togglePostLike(int $postId, int $userId): bool
    {
        $sql = "INSERT INTO post_likes (post_id, user_id) VALUES (:post_id, :user_id)
                ON DUPLICATE KEY UPDATE id = LAST_INSERT_ID(id)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':post_id', $postId, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        // 更新帖子点赞计数
        $this->updatePostLikeCount($postId);

        return true;
    }

    /**
     * 取消帖子点赞
     */
    public function unlikePost(int $postId, int $userId): bool
    {
        $sql = "DELETE FROM post_likes WHERE post_id = :post_id AND user_id = :user_id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':post_id', $postId, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        // 更新帖子点赞计数
        $this->updatePostLikeCount($postId);

        return true;
    }

    /**
     * 更新帖子点赞计数
     */
    private function updatePostLikeCount(int $postId): bool
    {
        $sql = "UPDATE forum_posts p
                SET like_count = (SELECT COUNT(*) FROM post_likes l WHERE l.post_id = p.id)
                WHERE p.id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $postId, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * 检查用户是否已点赞帖子
     */
    public function hasUserLikedPost(int $postId, int $userId): bool
    {
        $sql = "SELECT COUNT(*) as count FROM post_likes WHERE post_id = :post_id AND user_id = :user_id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':post_id', $postId, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch();

        return (int) $result['count'] > 0;
    }

    // ==================== 回复点赞相关操作 ====================

    /**
     * 获取所有回复（管理员用，支持分页和搜索）
     */
    public function getAllReplies(int $page = 1, int $limit = 20, string $search = ''): array
    {
        $offset = ($page - 1) * $limit;

        $sql = "SELECT r.*, u.username, p.title as post_title
                FROM forum_replies r
                LEFT JOIN users u ON r.user_id = u.id
                LEFT JOIN forum_posts p ON r.post_id = p.id
                WHERE 1=1";

        $params = [];

        if ($search) {
            $sql .= " AND (r.content LIKE :search OR u.username LIKE :search)";
            $params[':search'] = "%$search%";
        }

        $sql .= " ORDER BY r.created_at DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * 获取所有回复总数（管理员用）
     */
    public function getAllRepliesCount(string $search = ''): int
    {
        $sql = "SELECT COUNT(*) as total FROM forum_replies r
                LEFT JOIN users u ON r.user_id = u.id
                WHERE 1=1";
        $params = [];

        if ($search) {
            $sql .= " AND (r.content LIKE :search OR u.username LIKE :search)";
            $params[':search'] = "%$search%";
        }

        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        $result = $stmt->fetch();

        return (int) $result['total'];
    }

    /**
     * 根据 ID 获取单条回复
     */
    public function getReplyById(int $replyId): ?array
    {
        $sql = "SELECT r.*, u.username, u.avatar
                FROM forum_replies r
                LEFT JOIN users u ON r.user_id = u.id
                WHERE r.id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $replyId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch() ?: null;
    }

    /**
     * 更新回复内容
     */
    public function updateReply(int $replyId, string $content): bool
    {
        $sql = "UPDATE forum_replies SET content = :content WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':content', $content);
        $stmt->bindValue(':id', $replyId, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * 切换回复点赞状态
     */
    public function toggleReplyLike(int $replyId, int $userId): bool
    {
        $sql = "INSERT INTO reply_likes (reply_id, user_id) VALUES (:reply_id, :user_id)
                ON DUPLICATE KEY UPDATE id = LAST_INSERT_ID(id)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':reply_id', $replyId, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $this->updateReplyLikeCount($replyId);

        return true;
    }

    /**
     * 取消回复点赞
     */
    public function unlikeReply(int $replyId, int $userId): bool
    {
        $sql = "DELETE FROM reply_likes WHERE reply_id = :reply_id AND user_id = :user_id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':reply_id', $replyId, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $this->updateReplyLikeCount($replyId);

        return true;
    }

    /**
     * 更新回复点赞计数
     */
    private function updateReplyLikeCount(int $replyId): bool
    {
        $sql = "UPDATE forum_replies r
                SET like_count = (SELECT COUNT(*) FROM reply_likes l WHERE l.reply_id = r.id)
                WHERE r.id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $replyId, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * 检查用户是否已点赞回复
     */
    public function hasUserLikedReply(int $replyId, int $userId): bool
    {
        $sql = "SELECT COUNT(*) as count FROM reply_likes WHERE reply_id = :reply_id AND user_id = :user_id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':reply_id', $replyId, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch();

        return (int) $result['count'] > 0;
    }

    // ==================== 用户相关操作 ====================

    /**
     * 根据用户名获取用户
     */
    public function getUserByUsername(string $username): ?array
    {
        $sql = "SELECT * FROM users WHERE username = :username";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':username', $username);
        $stmt->execute();

        return $stmt->fetch() ?: null;
    }

    /**
     * 根据 ID 获取用户
     */
    public function getUserById(int $userId): ?array
    {
        $sql = "SELECT * FROM users WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch() ?: null;
    }

    /**
     * 创建用户
     */
    public function createUser(string $username, string $email, string $passwordHash): int
    {
        $sql = "INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password_hash)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':username', $username);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':password_hash', $passwordHash);
        $stmt->execute();

        return (int) $this->db->lastInsertId();
    }

    /**
     * 获取用户的帖子
     */
    public function getPostsByUserId(int $userId, int $page = 1, int $limit = 20): array
    {
        $offset = ($page - 1) * $limit;

        $sql = "SELECT p.*, (SELECT COUNT(*) FROM forum_replies r WHERE r.post_id = p.id) as reply_count
                FROM forum_posts p
                WHERE p.user_id = :user_id
                ORDER BY p.created_at DESC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // ==================== 统计相关操作 ====================

    /**
     * 获取论坛统计信息
     */
    public function getForumStats(): array
    {
        $sql = "SELECT
                    (SELECT COUNT(*) FROM users) as total_users,
                    (SELECT COUNT(*) FROM forum_posts) as total_posts,
                    (SELECT COUNT(*) FROM forum_replies) as total_replies";

        $stmt = $this->db->query($sql);

        return $stmt->fetch();
    }
}