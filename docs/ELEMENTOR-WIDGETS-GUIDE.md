# Elementor 动态小部件使用指南

## 🎉 完成的功能

### ✅ 5 个自定义动态小部件

所有小部件位于 **Renaissance 动态** 分类中：

1. **Media Reports**（媒体报道）
   - 双轮播显示：左侧内容 + 右侧图片
   - 可设置：文章数量、分类、排序方式
   - 自动同步切换

2. **Scientists List**（科学家列表）
   - 双行滚动动画
   - 可设置：显示数量（-1 表示全部）
   - 自动读取 Scientist CPT

3. **Cases List**（案例列表）
   - 网格布局卡片
   - 可设置：案例数量、列数（2/3/4 列）
   - 自动读取 Case CPT

4. **Announcements List**（公告列表）
   - 列表样式显示
   - 可设置：公告数量
   - 自动读取 Announcement CPT

5. **Video Tutorials**（视频教程）
   - 列表样式 + 播放按钮
   - 可设置：视频数量
   - 自动读取 Video CPT

---

## 📝 使用方式

### 编辑页面

1. 后台 → 页面 → 编辑任意页面
2. 点击 "使用 Elementor 编辑"
3. 点击 "+" 添加元素
4. 找到 **Renaissance 动态** 分类
5. 拖拽小部件到页面
6. 在左侧面板设置参数
7. 预览并发布

### 页面模板

主题已为以下页面创建了完整的静态内容：

#### Home 页面
- ✅ Hero Section（标题 + 视频背景 + 粒子动画）
- ✅ Company Info Tabs（公司介绍标签页）
- ✅ Mission & Vision（使命与愿景 + 地球动画）
- ✅ Feature Cards（4 个特性卡片）
- ✅ Media Reports 占位符 → **添加 Media Reports 小部件**

#### Research 页面
- ✅ Hero Section（标题 + 粒子背景）
- ✅ Research Features（3 个特性卡片）
- ✅ Scientists 占位符 → **添加 Scientists List 小部件**
- ✅ Cases 占位符 → **添加 Cases List 小部件**

#### Downloads 页面
- ✅ Hero Section（视频背景）
- ✅ Main Download Card（主下载卡片）
- ✅ Announcements 占位符 → **添加 Announcements List 小部件**
- ✅ Video Tutorials 占位符 → **添加 Video Tutorials 小部件**
- ✅ Get Started CTA

---

## 🌍 多语言支持

### Polylang 工作流程

1. **创建页面翻译**
   - 编辑页面 → Languages → Add new translation
   - 选择语言（中文/英文/法语）

2. **编辑翻译版本**
   - 每个语言的页面独立用 Elementor 编辑
   - 动态小部件自动适配当前语言的内容

3. **翻译内容**
   - 静态文本：在 Elementor 中直接编辑
   - 动态内容：小部件会根据 Polylang 当前语言查询对应文章

### 内容翻译

所有小部件支持 Polylang：
- ✅ 小部件名称已翻译（中/英/德）
- ✅ 控制项标签已翻译
- ✅ 动态查询内容会根据当前语言过滤

---

## 🎯 推荐的页面编辑流程

### Home 页面

1. 删除占位符文本
2. 在 "Media Reports Section" 中添加 **Media Reports** 小部件
3. 设置参数：
   - Number of Posts: 3
   - Category: All Categories
   - Order By: Date

### Research 页面

1. 删除占位符文本
2. 在 "Scientist Section" 中添加 **Scientists List** 小部件
3. 在 "Cases Section" 中添加 **Cases List** 小部件
4. 设置参数：
   - Scientists: -1 (显示全部)
   - Cases: 3, Columns: 3

### Downloads 页面

1. 删除占位符文本
2. 在 "Update Announcements" 区域添加 **Announcements List** 小部件
3. 在 "Video Tutorials" 区域添加 **Video Tutorials** 小部件
4. 设置参数：
   - Announcements: 1
   - Videos: 4

---

## ⚠️ 注意事项

### 权限控制

Downloads 页面的权限控制已移到页面模板中：
- 未登录用户 → 只显示登录/注册 CTA
- 普通用户 → 显示升级 Premium 提示
- Premium Member/管理员 → 显示完整内容

如需修改，请编辑 `page-templates/page-downloads.php`

### 小部件样式

所有小部件使用主题原有的 CSS 类名，样式 100% 匹配静态模板。

### Customizer 设置

⚠️ 旧的 Customizer 设置（首页、Research、Downloads）已不再使用，建议移除以避免混淆。

---

## 🚀 优势

### vs 传统 Customizer 方案

| 特性 | Customizer | Elementor 小部件 |
|------|------------|-----------------|
| 多语言支持 | ❌ 所有语言共享 | ✅ 完美支持 Polylang |
| 可视化编辑 | ⚠️ 有限 | ✅ 完全可视化 |
| 布局自由度 | ❌ 固定布局 | ✅ 完全自由 |
| 内容管理 | ⚠️ 混乱 | ✅ 页面级管理 |
| 用户体验 | ⚠️ 一般 | ✅ 极佳 |

### 未来扩展

- ✅ 易于添加新小部件
- ✅ 可创建小部件模板
- ✅ 支持导入/导出
- ✅ 完全兼容 Elementor Pro

---

**🎊 享受全新的页面编辑体验！**

