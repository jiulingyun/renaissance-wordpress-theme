# Main Download Card 权限控制指南

## 功能概述

Main Download Card 小部件现在支持基于用户登录状态和会员等级的权限控制。管理员可以灵活配置谁可以看到下载内容。

## 权限设置

在 Elementor 编辑器中，Main Download Card 小部件提供以下权限设置：

### 1. 需要登录 (Require Login)
- **开启**：只有已登录用户才能看到下载卡片
- **关闭**：所有访客都可以看到下载卡片
- **默认值**：开启

### 2. 需要高级会员 (Require Premium Membership)
- **开启**：只有拥有 `access_downloads` 权限的用户才能看到下载卡片
- **关闭**：所有已登录用户都可以看到下载卡片
- **默认值**：开启
- **注意**：此选项仅在"需要登录"开启时可用

### 3. 提示消息 (Fallback Message)
- 当用户没有权限时显示的提示文字
- 支持多行文本
- **默认值**：`This content is only available to Premium Members. Please login or upgrade your membership to access.`

## 用户角色和权限

### Premium Member 角色
主题在激活时会自动创建 `premium_member` 角色，该角色拥有以下权限：
- `read`：基本阅读权限
- `access_downloads`：下载访问权限

### Administrator 角色
管理员自动拥有 `access_downloads` 权限。

## 权限验证逻辑

```
如果"需要登录"开启：
  ├─ 用户未登录 → 显示提示消息（登录/注册按钮）
  └─ 用户已登录
      ├─ "需要高级会员"开启
      │   ├─ 用户有 access_downloads 权限 → 显示下载卡片
      │   └─ 用户无 access_downloads 权限 → 显示提示消息（升级会员按钮）
      └─ "需要高级会员"关闭 → 显示下载卡片
否则：
  └─ 显示下载卡片（所有人可见）
```

## 提示界面

当用户没有权限时，会显示一个美观的提示界面，包含：

### 未登录用户
- 🔒 锁定图标
- "Premium Content" 标题
- 自定义提示消息
- **登录** 按钮（跳转到 `/login/`）
- **注册** 按钮（跳转到 `/register/`）

### 已登录但无权限用户
- 🔒 锁定图标
- "Premium Content" 标题
- 自定义提示消息
- **升级会员** 按钮（跳转到 `/contact/`）

## 如何为用户分配权限

### 方法1：设置为 Premium Member 角色
1. 进入 WordPress 后台
2. 导航到：用户 → 所有用户
3. 选择要编辑的用户
4. 在"角色"下拉框中选择 **Premium Member**
5. 点击"更新用户"

### 方法2：为现有角色添加权限
```php
// 在 functions.php 或自定义插件中
$role = get_role('subscriber'); // 或其他角色
if ($role) {
    $role->add_cap('access_downloads');
}
```

### 方法3：使用插件
推荐使用以下插件来管理用户权限：
- **Members** - 用户角色和权限管理插件
- **User Role Editor** - 高级角色编辑器

## 使用场景

### 场景1：完全公开
- 需要登录：❌ 关闭
- 所有访客都可以看到并下载

### 场景2：仅限注册用户
- 需要登录：✅ 开启
- 需要高级会员：❌ 关闭
- 所有注册用户都可以下载

### 场景3：仅限高级会员（默认）
- 需要登录：✅ 开启
- 需要高级会员：✅ 开启
- 只有 Premium Member 和 Administrator 可以下载

## 样式定制

权限提示界面的样式定义在 `assets/css/custom-fixes.css` 中，可以根据需要自定义：

```css
.main-download-card.permission-required {
    /* 容器样式 */
}

.main-download-card.permission-required .permission-icon {
    /* 图标样式 */
}

.main-download-card.permission-required .permission-title {
    /* 标题样式 */
}

.main-download-card.permission-required .permission-message {
    /* 消息样式 */
}
```

## 多语言支持

所有权限相关的文本都支持多语言翻译：
- 中文翻译：`languages/zh_CN.po`
- 英文翻译：`languages/en_US.po`
- 法语翻译：`languages/fr_FR.po`

翻译的关键字符串：
- `Permission Settings`
- `Require Login`
- `Require Premium Membership`
- `Premium Content`
- `Login`
- `Register`
- `Upgrade Membership`

## 故障排除

### 问题：管理员也看不到下载卡片
**解决方案**：确保管理员角色有 `access_downloads` 权限。主题激活时会自动添加，但如果被移除，可以手动添加：
```php
$admin = get_role('administrator');
$admin->add_cap('access_downloads');
```

### 问题：Premium Member 看不到下载卡片
**解决方案**：
1. 检查用户角色是否正确设置为 `premium_member`
2. 检查 Elementor 小部件设置是否正确
3. 清除缓存（浏览器缓存和 WordPress 缓存）

### 问题：提示消息没有翻译
**解决方案**：
1. 确保已编译翻译文件（`.mo` 文件）
2. 清除 WordPress 翻译缓存
3. 检查 Polylang 语言设置

---

**Renaissance Theme** - Version 1.1.0

