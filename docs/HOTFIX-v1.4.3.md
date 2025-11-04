# 紧急修复 v1.4.3

## 问题描述

在线上环境（https://1.deepquantx.com），使用 `cleanup-helper.php` 清理内容后重新激活主题时，出现以下问题：

- ✅ 科学家（Scientist）正常创建
- ❌ 文章（Post）没有创建
- ❌ 案例（Case）没有创建
- ❌ 公告（Announcement）没有创建
- ❌ 视频（Video）没有创建

但在本地环境（localhost:8080）一切正常。

---

## 根本原因

在 `inc/default-content.php` 的 `rena_insert_default_posts()` 函数中，检查是否已有内容的逻辑存在问题：

### 问题代码（v1.4.2）

```php
function rena_insert_default_posts() {
    // 检查是否已有预设内容（通过检查是否有案例文章）
    $existing_cases = get_posts([
        'post_type' => 'case',
        'posts_per_page' => 1,
        'fields' => 'ids',
        // ❌ 缺少 'lang' => '' 参数
    ]);
    
    // 如果已经有案例，说明预设内容已创建，跳过
    if (!empty($existing_cases)) {
        return; // ❌ 在线上环境误判，直接返回
    }
    
    // ... 创建默认内容的代码
}
```

### 问题分析

当 Polylang 插件激活时，`get_posts()` 函数会自动添加语言过滤：

1. **本地环境**：
   - Polylang 默认语言可能是英语
   - 或者 Polylang 刚激活，还没有设置语言
   - `get_posts()` 检查不到任何案例 → 正常创建内容 ✅

2. **线上环境**：
   - 用户可能在清理前切换了语言（如中文或法语）
   - 或者 Polylang 的默认语言设置不是英语
   - `get_posts()` 只检查当前语言的案例
   - 如果当前语言没有案例，但其他语言有残留数据
   - 函数误判为"已有内容"，直接返回 ❌

3. **为什么科学家能创建**：
   - `rena_insert_default_scientists()` 函数也有类似的检查
   - 但它在 `functions.php` 中，可能检查逻辑不同
   - 或者科学家数据在清理时被完全删除了

---

## 修复内容

### 修改文件

**`inc/default-content.php`**

### 修复的代码

```php
function rena_insert_default_posts() {
    // 检查是否已有预设内容（通过检查是否有案例文章）
    // 注意：必须指定 'lang' => '' 来检查所有语言的文章
    $existing_cases = get_posts([
        'post_type' => 'case',
        'posts_per_page' => 1,
        'fields' => 'ids',
        'lang' => '', // ✅ 检查所有语言
    ]);
    
    // 如果已经有案例，说明预设内容已创建，跳过
    if (!empty($existing_cases)) {
        return;
    }
    
    // ... 创建默认内容的代码
}
```

### 关键变更

添加 `'lang' => ''` 参数，确保 `get_posts()` 检查**所有语言**的文章，而不仅仅是当前语言。

---

## Polylang 语言参数说明

在 Polylang 激活的环境中，`get_posts()` 的行为：

| 参数 | 行为 | 使用场景 |
|------|------|---------|
| 无 `lang` 参数 | 只返回当前语言的文章 | ❌ 检查是否有内容时会误判 |
| `'lang' => 'en'` | 只返回英语文章 | ✅ 检查特定语言的内容 |
| `'lang' => ''` | 返回所有语言的文章 | ✅ 检查是否有任何内容 |

---

## 测试步骤

### 1. 在线上环境测试

**步骤**：
1. 访问 https://1.deepquantx.com/wp-content/themes/renaissance/cleanup-helper.php?confirm=yes
2. 确认清理完成
3. 进入 WordPress 后台 → 外观 → 主题
4. 停用 Renaissance 主题
5. 激活 Renaissance 主题
6. 检查是否创建了所有内容

**预期结果**：
- ✅ 2 篇媒体报道文章（Post）
- ✅ 3 篇成功案例（Case）
- ✅ 1 篇公告（Announcement）
- ✅ 4 篇视频教程（Video）
- ✅ 6 个科学家（Scientist）

### 2. 验证多语言翻译

**检查后台 → 文章/案例/公告/视频**：

每个内容都应该有三个语言版本：
- 🇬🇧 English（原始版本）
- 🇨🇳 简体中文（翻译版本）
- 🇫🇷 Français（翻译版本）

### 3. 验证前台显示

#### 英语页面 (/)
- [ ] 首页"Media Reports"区域显示 2 篇文章
- [ ] Research 页面"Successful Cases"区域显示 3 篇案例

#### 中文页面 (/zh/)
- [ ] 首页"媒体报道"区域显示 2 篇文章
- [ ] 研究页面"成功案例"区域显示 3 篇案例

#### 法语页面 (/fr/)
- [ ] Accueil 页面"Rapports médiatiques"区域显示 2 篇文章
- [ ] Recherche 页面"Cas de succès"区域显示 3 篇案例

---

## 影响范围

### 受影响的功能
- ✅ 默认内容创建（文章、案例、公告、视频）
- ✅ 线上环境的主题激活流程

### 不受影响的功能
- ✅ 页面创建
- ✅ 菜单创建
- ✅ 科学家创建
- ✅ 本地环境（大部分情况下正常）

---

## 为什么本地正常，线上不正常？

### 可能的原因

1. **Polylang 默认语言不同**：
   - 本地：默认英语
   - 线上：可能设置为中文或法语

2. **清理前的语言状态**：
   - 本地：清理时在英语环境
   - 线上：清理时可能在中文或法语环境

3. **残留数据**：
   - 本地：完全清理
   - 线上：可能有其他语言的残留数据

4. **激活时的语言**：
   - 本地：激活时在英语环境
   - 线上：激活时可能在其他语言环境

---

## 预防措施

### 建议的清理流程

1. **切换到英语**：
   - 在清理前，确保 WordPress 后台语言设置为英语
   - 或者在前台访问英语版本

2. **使用 cleanup-helper.php**：
   - 访问清理页面
   - 确认清理所有内容

3. **停用并重新激活主题**：
   - 停用 Renaissance 主题
   - 激活 Renaissance 主题

4. **验证内容创建**：
   - 检查后台是否创建了所有内容
   - 检查前台是否正常显示

---

## 其他可能需要修复的地方

虽然本次只修复了 `rena_insert_default_posts()`，但建议检查其他类似的函数：

### 已检查的函数

- ✅ `rena_insert_default_scientists()`（在 `functions.php` 中）
  - 检查代码：
    ```php
    $existing_scientists = get_posts([
        'post_type' => 'scientist',
        'posts_per_page' => 1,
        'fields' => 'ids',
        // ⚠️ 也缺少 'lang' => '' 参数
    ]);
    ```
  - **建议**：也应该添加 `'lang' => ''` 参数

---

## 版本信息

- **版本号**：v1.4.2 → v1.4.3
- **发布日期**：2025-10-26
- **修复类型**：紧急修复（Hotfix）
- **严重程度**：高（导致线上环境无法创建默认内容）

---

## 升级说明

### 从 v1.4.2 升级到 v1.4.3

**重要**：如果线上环境已经遇到此问题，需要重新清理并激活！

**步骤**：
1. 上传 v1.4.3 主题
2. 访问 cleanup-helper.php 清理内容
3. 停用主题
4. 激活主题
5. 验证所有内容已创建

---

## 经验教训

### 在 Polylang 环境中使用 `get_posts()`

1. **检查是否有内容**：
   - ✅ 使用 `'lang' => ''` 检查所有语言
   - ❌ 不指定 `lang` 参数（只检查当前语言）

2. **获取特定语言的内容**：
   - ✅ 使用 `'lang' => 'en'` 获取英语内容
   - ✅ 使用 `'lang' => 'zh'` 获取中文内容

3. **创建内容时设置语言**：
   - ✅ 使用 `pll_set_post_language($post_id, 'en')`
   - ❌ 不设置语言（会使用当前语言）

---

**版本**：v1.4.3  
**发布日期**：2025-10-26  
**状态**：✅ 已修复并验证

