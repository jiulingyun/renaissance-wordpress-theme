# 页脚多语言配置指南

## 概述

页脚部分采用 **方案 A + 方案 B 混合** 实现多语言支持：

- **方案 A**：使用 Polylang String Translations 管理可编辑的长文本
- **方案 B**：使用 WordPress `.po/.mo` 翻译文件管理固定的短文本

---

## 一、使用 Polylang String Translations 的内容

这些内容可以在 WordPress Customizer 中编辑，并通过 Polylang 字符串翻译功能实现多语言。

### 1. Logo 下方描述文字 (`footer_description`)

**编辑位置**：
- WordPress 后台 → 外观 → 自定义 → Footer Settings → Logo 下方描述文字

**翻译位置**：
- WordPress 后台 → 语言 → 字符串翻译
- 搜索：`footer_description`
- 分组：`Renaissance Theme - Footer`

**默认值（英文）**：
```
All rights reserved. The information on this website is for informational and discussion purposes only and does not constitute any issuance. Issuance can only be made by delivering a confidential issuance memorandum to appropriate investors. Past performance is not a guarantee of future performance. www.renfundx.com is the only official website of Renaissance Technologies of Canada Ltd.. Renaissance Technologies and any of its affiliated companies do not operate any other public websites. Any websites claiming to be associated with our company or our funds are not legitimate.
```

### 2. WhatsApp 按钮文字 (`footer_whatsapp_text`)

**编辑位置**：
- WordPress 后台 → 外观 → 自定义 → Footer Settings → WhatsApp 按钮文字

**翻译位置**：
- WordPress 后台 → 语言 → 字符串翻译
- 搜索：`footer_whatsapp_text`
- 分组：`Renaissance Theme - Footer`

**默认值（英文）**：
```
WhatsApp Consultation
```

**建议翻译**：
- 简体中文：`WhatsApp咨询`
- 法语：`WhatsApp-Beratung`

### 3. 版权文字 (`footer_copyright`)

**编辑位置**：
- WordPress 后台 → 外观 → 自定义 → Footer Settings → 版权文字

**翻译位置**：
- WordPress 后台 → 语言 → 字符串翻译
- 搜索：`footer_copyright`
- 分组：`Renaissance Theme - Footer`

**默认值（英文）**：
```
© 2025 Renaissance Technologies of Canada Ltd.
```

---

## 二、使用 `.po/.mo` 翻译文件的内容

这些是固定的短文本，通过编辑 `.po` 文件进行翻译。

### Newsletter 区域

| 英文原文 | 翻译键 | 简体中文建议 | 法语建议 |
|---------|--------|------------|---------|
| Get free information | `Get free information` | 获取免费信息 | Kostenlose Informationen erhalten |
| In our weekly newsletter. | `In our weekly newsletter.` | 在我们的每周通讯中。 | In unserem wöchentlichen Newsletter. |
| Enter your email | `Enter your email` | 输入您的邮箱 | Geben Sie Ihre E-Mail ein |
| Subscribe | `Subscribe` | 订阅 | Abonnieren |

### Footer 菜单链接

| 英文原文 | 翻译键 | 简体中文建议 | 法语建议 |
|---------|--------|------------|---------|
| Privacy Policy | `Privacy Policy` | 隐私政策 | Datenschutzrichtlinie |
| Risk Warning | `Risk Warning` | 风险警示 | Risikowarnung |
| Contact Information | `Contact Information` | 联系我们 | Kontaktinformationen |
| Investor Relations | `Investor Relations` | 投资者关系 | Investor Relations |

**翻译文件位置**：
- 简体中文：`/languages/zh_CN.po`
- 法语：`/languages/fr_FR.po`

**编译命令**：
```bash
msgfmt zh_CN.po -o zh_CN.mo
msgfmt fr_FR.po -o fr_FR.mo
```

---

## 三、不需要翻译的内容

### WhatsApp 链接 (`footer_whatsapp_link`)

**编辑位置**：
- WordPress 后台 → 外观 → 自定义 → Footer Settings → WhatsApp 链接

**说明**：
- 这是一个 URL，全语言通用
- 默认值：`https://wa.me/message/7O5Y2WOR6HEPF1`

---

## 四、使用流程

### 场景 1：修改页脚描述文字

1. 进入 WordPress 后台 → 外观 → 自定义 → Footer Settings
2. 修改 "Logo 下方描述文字"
3. 点击"发布"保存
4. 进入 WordPress 后台 → 语言 → 字符串翻译
5. 搜索 `footer_description`
6. 为每种语言添加翻译
7. 保存翻译

### 场景 2：修改 Newsletter 标题

1. 编辑 `/languages/zh_CN.po` 文件
2. 找到 `msgid "Get free information"`
3. 修改 `msgstr "获取免费信息"`
4. 编译：`msgfmt zh_CN.po -o zh_CN.mo`
5. 刷新网站前台

---

## 五、技术实现原理

### Polylang String Translations

```php
// 注册字符串（functions.php）
if (function_exists('pll_register_string')) {
    $footer_description = get_theme_mod('footer_description', '默认值');
    pll_register_string('footer_description', $footer_description, 'Renaissance Theme - Footer');
}

// 输出翻译（footer-site.php）
$footer_description = get_theme_mod('footer_description', '默认值');
if (function_exists('pll__')) {
    $footer_description = pll__($footer_description);
}
echo wp_kses_post($footer_description);
```

### WordPress 翻译函数

```php
// 输出翻译文本
<?php echo esc_html__('Get free information', 'renaissance'); ?>

// 输出翻译属性
placeholder="<?php echo esc_attr__('Enter your email', 'renaissance'); ?>"
```

---

## 六、优势总结

### 方案 A（Polylang String Translations）
- ✅ 保留 Customizer 可视化编辑体验
- ✅ 适合长文本和频繁修改的内容
- ✅ 无需编辑代码或 `.po` 文件

### 方案 B（`.po/.mo` 翻译文件）
- ✅ 符合 WordPress 标准
- ✅ 适合固定的短文本
- ✅ 翻译管理更集中

### 混合方案
- ✅ 结合两者优势
- ✅ 灵活性最高
- ✅ 用户体验最佳

---

## 七、注意事项

1. **首次使用**：激活主题后，需要访问前台一次，触发字符串注册
2. **修改 Customizer 内容后**：需要重新访问前台，Polylang 才能检测到新字符串
3. **`.po` 文件修改后**：必须编译为 `.mo` 文件才能生效
4. **字符串翻译界面**：如果找不到字符串，尝试刷新页面或清空缓存

---

**版本**：v1.3.3  
**更新日期**：2025-10-26

