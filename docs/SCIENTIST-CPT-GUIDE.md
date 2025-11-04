# 工程师自定义文章类型 (Scientist CPT) - 使用指南

## 🎉 重大更新：工程师数据模型升级！

之前工程师数据存储在首页的 ACF 固定字段中（最多 10 个），现在升级为**完全独立的自定义文章类型**！

---

## ✨ 升级优势

### 之前的方案（已弃用）
- ❌ 固定数量限制（最多 10 个）
- ❌ 数据绑定在 Research 页面
- ❌ 无法单独管理工程师
- ❌ 无法排序和筛选

### 新方案（Scientist CPT）
- ✅ **无限数量**：想添加多少工程师都可以
- ✅ **独立管理**：后台有专门的"Scientists"菜单
- ✅ **完整 CRUD**：创建、编辑、删除、搜索
- ✅ **自定义排序**：可设置显示顺序
- ✅ **丰富字段**：头像、职位、研究领域、个人链接
- ✅ **多语言支持**：配合 Polylang 完美工作

---

## 📦 Scientist 自定义文章类型

### 文章类型信息

| 属性 | 值 |
|------|-----|
| 内部名称 | `scientist` |
| 单数名称 | Scientist |
| 复数名称 | Scientists |
| URL Slug | `/scientist/` |
| 后台菜单图标 | `dashicons-groups` |
| 支持功能 | 标题（姓名）、编辑器（简介）、特色图片（头像）、自定义字段 |

### 自定义字段（ACF）

#### 1. **职位/头衔** (`scientist_position`)
- 类型：文本
- 示例：`Senior Quantitative Analyst`
- 用途：显示工程师的职位或学术头衔

#### 2. **研究领域** (`scientist_field`)
- 类型：文本
- 示例：`Machine Learning, Quantitative Finance`
- 用途：显示工程师的专业领域

#### 3. **个人主页链接** (`scientist_url`)
- 类型：URL
- 示例：`https://example.com`
- 用途：链接到工程师的个人网站或学术主页

#### 4. **显示顺序** (`display_order`)
- 类型：数字
- 默认值：0
- 用途：控制工程师在页面上的显示顺序
- 说明：数字越小越靠前，相同数字按发布日期排序

---

## 🔧 如何使用

### 添加新工程师

1. **进入后台**
   - WordPress 后台 → `Scientists` → `Add New`

2. **填写基本信息**
   - **标题**：工程师的姓名（例如：`Dr. Michael Chen`）
   - **特色图片**：上传工程师的头像照片
   - **内容编辑器**（可选）：写一段详细的个人简介

3. **填写自定义字段**
   向下滚动到"工程师详情"面板：
   - **职位/头衔**：例如 `Senior Quantitative Analyst`
   - **研究领域**：例如 `Machine Learning, High-Frequency Trading`
   - **个人主页链接**：例如 `https://example.com/profile`
   - **显示顺序**：填写一个数字，如 `1`（数字越小越靠前）

4. **发布**
   - 点击右上角的"发布"按钮

5. **查看效果**
   - 前往网站的 Research 页面
   - 新工程师会自动出现在工程师列表中

---

## 📊 Research 页面如何显示

### 自动渲染逻辑

Research 页面会自动从 `scientist` 自定义文章类型读取数据：

```php
// 查询所有工程师
$scientists_args = [
    'post_type' => 'scientist',
    'posts_per_page' => -1,           // 显示所有
    'orderby' => 'meta_value_num date', // 先按 display_order 排序
    'meta_key' => 'display_order',
    'order' => 'ASC',
];
```

### 显示规则

1. **排序**：
   - 首先按 `display_order` 字段升序排列
   - 如果 `display_order` 相同，按发布日期排序

2. **分行显示**：
   - 工程师会被**平均分成两行**
   - 第一行：从右向左无缝滚动
   - 第二行：从左向右无缝滚动

3. **显示内容**：
   - **头像**：特色图片（如果没有设置则使用占位图）
   - **姓名**：文章标题
   - **角色描述**：组合显示 `职位 | 研究领域`
   - 如果职位和领域都为空，则显示摘要或简介

---

## 🎨 前端效果

### 工程师卡片结构

```html
<div class="scientist-card">
    <div class="scientist-avatar">
        <img src="头像URL" alt="姓名">
    </div>
    <div class="scientist-info">
        <h4 class="scientist-name">Dr. Michael Chen</h4>
        <p class="scientist-role">Senior Quantitative Analyst | Machine Learning</p>
    </div>
</div>
```

### 无缝滚动动画

- 每行卡片会**复制一次**以实现无缝循环滚动
- 第一行从右向左滚动
- 第二行从左向右滚动
- 鼠标悬停时暂停滚动

---

## 🌍 多语言支持

### 配合 Polylang 使用

1. **安装 Polylang 插件**
   - 确保已安装并激活 Polylang

2. **创建工程师的翻译版本**
   - 编辑任意工程师文章
   - 在右侧"Languages"面板中，点击"Add new translation"
   - 选择目标语言（例如：中文、英文）
   - 填写翻译内容

3. **前端自动切换**
   - 用户切换网站语言时，工程师列表会自动显示对应语言的版本

---

## 🔄 从旧方案迁移

### 旧方案：Research 页面 ACF 字段

之前的工程师数据存储在 Research 页面的以下字段中：
- `scientist_1` 到 `scientist_10`（Group 字段）

### 迁移步骤

1. **进入 Research 页面编辑**
   - 查看现有的工程师数据

2. **手动创建 Scientist 文章**
   - 对每个工程师，在后台创建一篇新的 `Scientist` 文章
   - 复制姓名、头像、职位、研究领域等信息

3. **发布所有工程师**

4. **关闭旧开关**（可选）
   - Research 页面的"使用自定义工程师列表"开关可以关闭
   - 新方案不再依赖这个开关

---

## 💡 使用技巧

### 1. 如何设置显示顺序？

**场景**：我想让某个工程师排在最前面。

**方法**：
1. 编辑该工程师文章
2. 将"显示顺序"设为 `1`
3. 其他工程师设为 `2`, `3`, `4`...

### 2. 如何临时隐藏某个工程师？

**方法**：
1. 编辑该工程师文章
2. 点击"快速编辑"
3. 将状态改为"草稿"
4. 草稿状态的工程师不会在前台显示

### 3. 如何确保两行工程师数量平衡？

- 系统会自动平均分配
- 总数为奇数时，第一行会多一个工程师
- 例如：7个工程师 → 第一行4个，第二行3个

### 4. 如何设置默认头像？

如果工程师没有设置特色图片，系统会自动使用以下占位图：
- 第一行工程师：`scientist-1.jpg`
- 第二行工程师：`scientist-4.jpg`

---

## 📝 字段填写示例

### 示例工程师 1

```
标题：Dr. Michael Chen
特色图片：[上传头像照片]

工程师详情：
  - 职位/头衔：Senior Quantitative Analyst
  - 研究领域：Machine Learning, Algorithmic Trading
  - 个人主页链接：https://example.com/michael-chen
  - 显示顺序：1

内容（可选）：
Dr. Michael Chen has over 15 years of experience in quantitative finance...
```

### 示例工程师 2

```
标题：Prof. Sarah Williams
特色图片：[上传头像照片]

工程师详情：
  - 职位/头衔：Professor of Financial Engineering
  - 研究领域：Risk Management, Portfolio Optimization
  - 个人主页链接：
  - 显示顺序：2

内容（可选）：
Prof. Williams leads the quantitative research team...
```

---

## ⚠️ 注意事项

### 1. ACF 插件必需

- 本功能依赖 ACF（Advanced Custom Fields）插件
- 请确保 ACF 已安装并激活
- 免费版 ACF 完全够用

### 2. 头像图片尺寸

- 推荐尺寸：**400x400px** 或更大
- 图片会自动裁剪为圆形
- 支持 JPG、PNG、WebP 格式

### 3. 简介字数

- 角色描述会显示在工程师卡片上
- 建议控制在 **100-150 字符**内
- 过长的内容会被自动截断

### 4. 显示顺序冲突

- 如果多个工程师的 `display_order` 相同
- 系统会按**发布日期**排序（最新的在前）

---

## 🛠️ 开发者信息

### 相关文件

| 文件 | 说明 |
|------|------|
| `functions.php` (Line 142-165) | 注册 Scientist CPT |
| `inc/acf-fields.php` (Line 437-490) | 注册 ACF 字段 |
| `page-templates/page-research.php` (Line 18-80) | 查询和显示逻辑 |
| `page-templates/page-research.php` (Line 146-213) | 前端 HTML 渲染 |

### WP_Query 参数

```php
[
    'post_type' => 'scientist',
    'posts_per_page' => -1,
    'orderby' => 'meta_value_num date',
    'meta_key' => 'display_order',
    'order' => 'ASC',
]
```

### ACF 字段获取

```php
$position = get_field('scientist_position');  // 职位
$field = get_field('scientist_field');        // 研究领域
$url = get_field('scientist_url');            // 个人链接
$order = get_field('display_order');          // 显示顺序
```

---

## 🎊 总结

### 旧方案 vs 新方案

| 特性 | 旧方案（ACF Group） | 新方案（Scientist CPT） |
|------|-------------------|----------------------|
| 最大数量 | 10 个 | ✅ 无限 |
| 后台管理 | 在 Research 页面 | ✅ 独立菜单 |
| 搜索功能 | ❌ 无 | ✅ 支持 |
| 排序功能 | ❌ 固定 | ✅ 自定义 |
| 多语言 | ⚠️ 复杂 | ✅ 完美支持 |
| 数据复用 | ❌ 无 | ✅ 可扩展 |

### 下一步

1. ✅ **现在就试试**：添加第一个工程师
2. ✅ **迁移数据**：将旧的 10 个工程师迁移到新系统
3. ✅ **配合 Polylang**：为工程师添加多语言翻译
4. ✅ **优化头像**：使用高质量、统一风格的头像照片

---

**🎉 享受全新的工程师管理体验吧！**

