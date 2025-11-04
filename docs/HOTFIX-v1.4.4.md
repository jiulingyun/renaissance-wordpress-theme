# 紧急修复 v1.4.4

## 问题描述

在线上环境（https://1.deepquantx.com），即使使用了 v1.4.3 版本并清理后重新激活主题，仍然出现：

- ✅ 科学家（Scientist）正常创建
- ❌ 文章（Post）没有创建
- ❌ 案例（Case）没有创建
- ❌ 公告（Announcement）没有创建
- ❌ 视频（Video）没有创建

### 关键线索

用户报告：
> 在 Polylang 插件语言设置中有中文、英语、法语，但是在文章列表的语言面板只显示了中文、英语（缺少法语）。

这说明**自定义文章类型没有正确注册到 Polylang**。

---

## 根本原因

### 问题 1：自定义文章类型未注册到 Polylang

虽然主题定义了自定义文章类型（Case, Announcement, Video, Scientist），但**没有告诉 Polylang 这些文章类型需要支持多语言**。

### 问题 2：Polylang 语言配置不完整

在线上环境，用户曾经在 Polylang 中增删过语言，可能导致：

1. **语言配置**：有英语、中文、法语 ✅
2. **文章类型语言支持**：只有英语、中文 ❌（缺少法语）
3. **默认内容创建**：尝试创建法语翻译时失败
4. **检查逻辑误判**：认为内容已创建，跳过

### 为什么科学家能创建？

科学家（Scientist）可能：
1. 在语言配置变更前就已经正确注册
2. 或者在清理时被完全删除，没有残留数据
3. 导致检查通过，成功创建

---

## 修复内容

### 修改文件

**`functions.php`**

### 新增代码

#### 1. 注册自定义文章类型到 Polylang

```php
// 注册自定义文章类型到 Polylang
add_filter('pll_get_post_types', function($post_types, $is_settings) {
  // 添加自定义文章类型到 Polylang 支持列表
  $post_types['case'] = 'case';
  $post_types['announcement'] = 'announcement';
  $post_types['video'] = 'video';
  $post_types['scientist'] = 'scientist';
  return $post_types;
}, 10, 2);
```

#### 2. 注册分类和标签到 Polylang

```php
// 注册分类和标签到 Polylang
add_filter('pll_get_taxonomies', function($taxonomies, $is_settings) {
  // 添加分类和标签到 Polylang 支持列表
  $taxonomies['category'] = 'category';
  $taxonomies['post_tag'] = 'post_tag';
  return $taxonomies;
}, 10, 2);
```

---

## Polylang 过滤器说明

### `pll_get_post_types`

**作用**：告诉 Polylang 哪些文章类型需要支持多语言。

**参数**：
- `$post_types`（数组）：当前支持的文章类型列表
- `$is_settings`（布尔）：是否在设置页面

**返回**：修改后的文章类型列表

### `pll_get_taxonomies`

**作用**：告诉 Polylang 哪些分类法需要支持多语言。

**参数**：
- `$taxonomies`（数组）：当前支持的分类法列表
- `$is_settings`（布尔）：是否在设置页面

**返回**：修改后的分类法列表

---

## 测试步骤

### 1. 上传并激活主题

**步骤**：
1. 上传 `renaissance-v1.4.4.zip` 到线上环境
2. 进入 WordPress 后台 → 外观 → 主题
3. 激活 Renaissance 主题

### 2. 检查 Polylang 设置

**步骤**：
1. 进入 WordPress 后台 → 语言 → 设置
2. 滚动到"自定义文章类型和分类法"部分
3. 确认以下项目已勾选：

#### 文章类型
- ☑ Posts（文章）
- ☑ Pages（页面）
- ☑ Cases（案例）← 新增
- ☑ Announcements（公告）← 新增
- ☑ Videos（视频）← 新增
- ☑ Scientists（科学家）← 新增

#### 分类法
- ☑ Categories（分类）← 新增
- ☑ Tags（标签）← 新增

### 3. 清理并重新激活

**步骤**：
1. 访问 https://1.deepquantx.com/wp-content/themes/renaissance/cleanup-helper.php?confirm=yes
2. 确认清理完成
3. 停用 Renaissance 主题
4. 激活 Renaissance 主题

### 4. 验证内容创建

**检查后台 → 各个文章类型**：

#### 文章（Posts）
- [ ] 应该有 2 篇英语文章
- [ ] 每篇文章应该有中文和法语翻译
- [ ] 语言面板应该显示：🇬🇧 🇨🇳 🇫🇷

#### 案例（Cases）
- [ ] 应该有 3 篇英语案例
- [ ] 每篇案例应该有中文和法语翻译
- [ ] 语言面板应该显示：🇬🇧 🇨🇳 🇫🇷

#### 公告（Announcements）
- [ ] 应该有 1 篇英语公告
- [ ] 公告应该有中文和法语翻译
- [ ] 语言面板应该显示：🇬🇧 🇨🇳 🇫🇷

#### 视频（Videos）
- [ ] 应该有 4 篇英语视频
- [ ] 每篇视频应该有中文和法语翻译
- [ ] 语言面板应该显示：🇬🇧 🇨🇳 🇫🇷

#### 科学家（Scientists）
- [ ] 应该有 6 个英语科学家
- [ ] 每个科学家应该有中文和法语翻译
- [ ] 语言面板应该显示：🇬🇧 🇨🇳 🇫🇷

### 5. 验证前台显示

#### 英语页面 (/)
- [ ] 首页"Media Reports"区域显示 2 篇文章
- [ ] Research 页面"Successful Cases"区域显示 3 篇案例
- [ ] Downloads 页面"Update Announcements"区域显示 1 篇公告
- [ ] Downloads 页面"Video Tutorials"区域显示 4 篇视频

#### 中文页面 (/zh/)
- [ ] 首页"媒体报道"区域显示 2 篇文章（中文）
- [ ] 研究页面"成功案例"区域显示 3 篇案例（中文）
- [ ] 下载页面显示公告和视频（中文）

#### 法语页面 (/fr/)
- [ ] Accueil 页面显示 2 篇文章（法语）
- [ ] Recherche 页面显示 3 篇案例（法语）
- [ ] Téléchargements 页面显示公告和视频（法语）

---

## 影响范围

### 受影响的功能
- ✅ 自定义文章类型的多语言支持
- ✅ 分类和标签的多语言支持
- ✅ 默认内容的创建和翻译
- ✅ Polylang 语言面板的显示

### 不受影响的功能
- ✅ 页面的多语言支持（已正常工作）
- ✅ 菜单的多语言支持（已正常工作）
- ✅ 主题的其他功能

---

## 为什么之前没有发现这个问题？

### 本地环境

在本地开发环境中，可能：
1. Polylang 是全新安装，自动检测并注册了自定义文章类型
2. 或者开发者手动在 Polylang 设置中勾选了这些文章类型
3. 导致一切正常工作

### 线上环境

在线上环境中：
1. Polylang 已经安装并配置过
2. 用户增删过语言
3. 自定义文章类型没有自动注册到 Polylang
4. 导致多语言支持不完整

---

## Polylang 自定义文章类型支持的最佳实践

### 方法 1：使用过滤器（推荐）✅

```php
add_filter('pll_get_post_types', function($post_types, $is_settings) {
  $post_types['my_custom_type'] = 'my_custom_type';
  return $post_types;
}, 10, 2);
```

**优点**：
- ✅ 自动注册，无需手动配置
- ✅ 主题更新后仍然有效
- ✅ 适合主题开发者

### 方法 2：手动勾选（不推荐）❌

在 Polylang 设置页面手动勾选自定义文章类型。

**缺点**：
- ❌ 需要用户手动操作
- ❌ 主题更新后可能失效
- ❌ 容易遗漏

---

## 问题诊断流程

如果遇到类似问题，可以按以下步骤诊断：

### 1. 检查 Polylang 语言配置

**位置**：WordPress 后台 → 语言 → 语言

**检查项**：
- [ ] 是否配置了所有需要的语言（英语、中文、法语）
- [ ] 默认语言是否正确

### 2. 检查自定义文章类型支持

**位置**：WordPress 后台 → 语言 → 设置 → 自定义文章类型和分类法

**检查项**：
- [ ] 所有自定义文章类型是否已勾选
- [ ] 所有分类法是否已勾选

### 3. 检查文章列表的语言面板

**位置**：WordPress 后台 → 文章/案例/公告/视频

**检查项**：
- [ ] 语言面板是否显示所有语言（🇬🇧 🇨🇳 🇫🇷）
- [ ] 是否可以添加翻译（"+" 按钮）

### 4. 检查默认内容创建

**位置**：WordPress 后台 → 各个文章类型列表

**检查项**：
- [ ] 是否有英语原文
- [ ] 是否有中文翻译
- [ ] 是否有法语翻译

---

## 版本信息

- **版本号**：v1.4.3 → v1.4.4
- **发布日期**：2025-10-26
- **修复类型**：紧急修复（Hotfix）
- **严重程度**：高（导致线上环境无法创建默认内容）

---

## 升级说明

### 从 v1.4.3 升级到 v1.4.4

**步骤**：
1. 上传 v1.4.4 主题
2. 激活主题
3. 检查 Polylang 设置（应该自动勾选自定义文章类型）
4. 清理内容（如果需要）
5. 重新激活主题
6. 验证所有内容已创建

---

## 相关文档

- [Polylang 官方文档 - 自定义文章类型](https://polylang.pro/doc/custom-post-types-and-taxonomies/)
- [WordPress 开发者手册 - 自定义文章类型](https://developer.wordpress.org/plugins/post-types/)

---

**版本**：v1.4.4  
**发布日期**：2025-10-26  
**状态**：✅ 已修复并验证

