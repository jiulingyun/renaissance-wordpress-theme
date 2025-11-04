# 自定义文章类型说明文档

本主题使用了多个自定义文章类型（Custom Post Types, CPT）来管理不同类型的内容。

## 文章类型列表

### 1. Cases（案例）
**用途**：展示成功案例和项目案例  
**模板**：`single-case.php`  
**归档页**：`archive-case.php`  
**URL示例**：`/cases/case-name/`

#### 支持的功能
- 标题（Title）
- 编辑器（Editor）
- 特色图片（Thumbnail）
- 摘要（Excerpt）
- 自定义字段（Custom Fields）
- 标签（Tags）

#### 自定义字段（Custom Fields）

| 字段名称 | 字段类型 | 说明 | 示例值 |
|---------|---------|------|--------|
| `case_category` | 文本 | 案例分类标签 | "Algorithmic Trading" |
| `case_subtitle` | 文本区域 | 案例副标题 | "Revolutionary microsecond-level trading system" |
| `case_metrics` | **Repeater** | 性能指标（可添加多个）⭐ | - |
| ↳ `metric_value` | 文本 | 指标数值 | "847%" |
| ↳ `metric_label` | 文本 | 指标标签 | "Return on Investment" |
| `project_duration` | 文本 | 项目周期 | "36 months" |
| `project_team_size` | 文本 | 团队规模 | "12 specialists" |
| `project_markets` | 文本 | 市场范围 | "Global Equities, FX, Futures" |
| `project_technology` | 文本 | 使用技术 | "C++, FPGA, Machine Learning" |
| `key_features` | **Repeater** | 关键特性（可添加多个）⭐ | - |
| ↳ `feature_text` | 文本 | 特性描述 | "Sub-microsecond execution latency" |

**⭐ 新功能**：`case_metrics` 和 `key_features` 现在使用 Repeater 字段，可以添加任意数量的指标和特性！

---

### 2. Announcements（公告）
**用途**：发布更新公告和补丁下载通知  
**模板**：`single-announcement.php`  
**归档页**：`archive-announcement.php`（默认）  
**URL示例**：`/announcements/announcement-name/`

#### 支持的功能
- 标题（Title）
- 编辑器（Editor）
- 特色图片（Thumbnail）
- 摘要（Excerpt）
- 自定义字段（Custom Fields）
- 标签（Tags）- **用于显示"System Update"等分类标签**

#### 自定义字段（Custom Fields）

| 字段名称 | 字段类型 | 说明 | 示例值 |
|---------|---------|------|--------|
| `announcement_category` | 文本 | 公告分类（可选，留空则使用第一个标签） | "System Update" |
| `announcement_subtitle` | 文本区域 | 公告副标题 | "Next-generation machine learning algorithms" |
| `update_version` | 文本 | 更新版本号 | "v4.1.0" |
| `update_size` | 文本 | 更新大小 | "245 MB" |
| `update_compatibility` | 文本 | 兼容性 | "All Systems" |
| `update_deployment` | 文本 | 部署方式 | "Automatic" |
| `announcement_metrics` | **Repeater** | 性能指标（可选，可添加多个）⭐ | - |
| ↳ `metric_value` | 文本 | 指标数值 | "+23%" |
| ↳ `metric_label` | 文本 | 指标标签 | "Prediction Accuracy" |

**⭐ 新功能**：性能指标现在使用 Repeater 字段，可以添加任意数量的指标！

#### 使用建议
- 标签用于区分公告类型，如"System Update"、"Security Patch"、"Feature Release"等
- 第一个标签会在 Downloads 页面和详情页作为分类显示
- 摘要用于列表页显示，建议250字符以内
- 如果不填写文章内容，会自动显示默认的结构化布局
- 性能指标是可选的，填写后会在详情页显示指标卡片

---

### 3. Videos（视频教程）
**用途**：视频教程和培训内容  
**模板**：`single-video.php`  
**归档页**：`archive-video.php`（默认）  
**URL示例**：`/videos/video-name/`

#### 支持的功能
- 标题（Title）
- 编辑器（Editor）- **在这里插入视频或添加视频URL**
- 特色图片（Thumbnail）
- 摘要（Excerpt）
- 自定义字段（Custom Fields）
- 标签（Tags）

#### 自定义字段（Custom Fields）

| 字段名称 | 说明 | 示例值 |
|---------|------|--------|
| `video_category` | 视频分类 | "Tutorial" |
| `video_duration` | 视频时长（可选，会自动获取） | "12:34" |
| `video_level` | 难度等级 | "Beginner", "Intermediate", "Advanced" |
| `video_subtitle` | 视频副标题 | "Complete guide to setup" |
| `video_views` | 观看次数 | "1234" |
| `video_language` | 视频语言 | "English" |
| `video_subtitles` | 字幕可用性 | "Available" |
| `instructor_name` | 讲师姓名 | "Dr. Michael Chen" |
| `instructor_title` | 讲师头衔 | "Senior Quantitative Analyst" |
| `instructor_bio` | 讲师简介 | "15+ years experience..." |

#### 视频嵌入方法

**方法1：使用 WordPress 视频块（推荐）**
1. 在编辑器中点击"+"添加块
2. 选择"视频"块
3. 上传或选择视频文件
4. WordPress 会自动获取视频时长

**方法2：直接在内容中添加视频URL**
```
在文章内容中粘贴视频URL，如：
https://example.com/video.mp4
```

**方法3：使用 HTML 代码**
```html
<video controls>
  <source src="https://example.com/video.mp4" type="video/mp4">
</video>
```

---

### 4. Post（文章）- 默认类型
**用途**：常规文章，主要用于首页"媒体报道"  
**模板**：`single.php`  
**归档页**：`archive.php` 或 `index.php`  
**URL示例**：`/post-name/`

#### 支持的功能
- 标题、编辑器、特色图片、摘要、标签、分类

---

## 如何添加自定义字段

### 方法一：手动添加（不推荐）

1. 编辑文章时，滚动到底部找到"自定义字段"面板
2. 如果没有看到，点击右上角"⋮"→"选项"→勾选"自定义字段"
3. 点击"添加新"或"输入新字段"
4. 输入字段名称和值
5. 点击"添加自定义字段"

### 方法二：使用 ACF 插件（强烈推荐）⭐

1. 安装 **Advanced Custom Fields (ACF)** 插件
2. 创建字段组，关联到对应的文章类型
3. 添加所需字段，设置字段类型和选项
4. 编辑文章时会看到友好的输入界面

#### ACF 字段组配置示例

**案例（Cases）字段组**
```
字段组名称：Case Details
位置规则：文章类型 等于 Case

字段列表：
- case_category (文本)
- case_subtitle (文本区域)
- metric_1_value (文本)
- metric_1_label (文本)
- metric_2_value (文本)
- metric_2_label (文本)
- metric_3_value (文本)
- metric_3_label (文本)
- project_duration (文本)
- project_team_size (文本)
- project_markets (文本)
- project_technology (文本)
- key_features (文本区域，说明：每行一个特性）
```

**公告（Announcements）字段组**
```
字段组名称：Announcement Details
位置规则：文章类型 等于 Announcement

字段列表：
- announcement_category (文本，可选)
- announcement_subtitle (文本区域)
- update_version (文本)
- update_size (文本)
- update_compatibility (文本)
- update_deployment (文本)
- metric_1_value (文本，可选)
- metric_1_label (文本，可选)
- metric_2_value (文本，可选)
- metric_2_label (文本，可选)
- metric_3_value (文本，可选)
- metric_3_label (文本，可选)
```

**视频（Videos）字段组**
```
字段组名称：Video Details
位置规则：文章类型 等于 Video

字段列表：
- video_category (文本)
- video_subtitle (文本区域)
- video_duration (文本，可选 - 系统会自动获取)
- video_level (选择: Beginner/Intermediate/Advanced)
- video_language (文本)
- video_subtitles (文本)
- instructor_name (文本)
- instructor_title (文本)
- instructor_bio (文本区域)
```

---

## Downloads 页面配置

Downloads 页面会自动从以下文章类型读取内容：

- **更新公告区域**：从 `announcement` 类型读取
- **视频教程区域**：从 `video` 类型读取

在 `后台 → 外观 → 自定义 → Downloads 页面` 中可以选择：
- 显示哪个分类的文章
- 显示多少篇文章

---

## 视频时长自动获取

系统会自动尝试从以下来源获取视频时长：

1. **自定义字段** `video_duration`（如果设置）
2. **视频附件元数据**（从WordPress媒体库上传的视频）
3. **文章附件**（附加到文章的视频文件）
4. **默认值** `--:--`（如果都获取不到）

**最佳实践**：直接在文章中插入视频或上传视频到媒体库，系统会自动获取时长。

---

## 后台菜单位置

启用主题后，后台左侧菜单会出现：

- **文章**（默认，用于媒体报道）
- **Cases**（案例，图标：文件夹）
- **Announcements**（公告，图标：广播）
- **Videos**（视频教程，图标：视频）

---

## 注意事项

1. **第一次启用主题后**，需要到 `设置 → 固定链接` 点击"保存更改"来刷新URL规则
2. **自定义字段**是可选的，如果不设置会使用默认值
3. **标签**在不同文章类型中的作用不同：
   - Cases：用于关联相关案例
   - Announcements：用于显示分类标签（如"重要更新"）
   - Videos：用于推荐相关视频
4. **Elementor 兼容**：所有文章类型都支持 Elementor 编辑器

---

## 快速开始

### 创建第一个案例

1. 后台 → Cases → 添加新
2. 输入标题和内容
3. 设置特色图片
4. 添加自定义字段（使用 ACF 插件更方便）
5. 发布

### 创建第一个视频教程

1. 后台 → Videos → 添加新
2. 输入标题
3. 在内容中插入视频（使用视频块或粘贴URL）
4. 设置摘要（用于列表显示）
5. 添加标签（可选，用于关联推荐）
6. 发布

### 创建第一个公告

1. 后台 → Announcements → 添加新
2. 输入标题和内容
3. 添加标签（如"重要更新"、"补丁下载"）
4. 设置摘要（建议250字符）
5. 发布

---

## 技术支持

如有问题或需要帮助，请联系主题开发团队。

