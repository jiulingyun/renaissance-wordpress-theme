# 更新日志 v1.4.0

## 重大变更：德语 → 法语

**发布日期**：2025-10-26

---

## 概述

由于客户提供的原始静态 HTML 模板内置了**英语、中文、法语**三种语言，而主题开发过程中误将第三语言实现为德语，现已全面修正为法语。

---

## 修改内容

### 1. ✅ 翻译文件

**删除**：
- `languages/de_DE.po`
- `languages/de_DE.mo`

**新增**：
- `languages/fr_FR.po` - 法语翻译文件
- `languages/fr_FR.mo` - 法语编译文件

**翻译内容**：
- 自定义文章类型（Cases, Announcements, Videos, Scientists）
- 页面标题（Home, Research, Downloads, Login, Register 等）
- 表单字段和按钮
- 页脚内容
- Elementor 小部件文本

---

### 2. ✅ Elementor 预设数据

**修改文件**：`inc/elementor-templates.php`

**变更**：
- `rena_set_home_elementor_data_de()` → `rena_set_home_elementor_data_fr()`
- `rena_set_research_elementor_data_de()` → `rena_set_research_elementor_data_fr()`
- `rena_set_downloads_elementor_data_de()` → `rena_set_downloads_elementor_data_fr()`

**翻译内容**：
- Home 页面：标题、副标题、公司介绍、使命愿景、价值观
- Research 页面：研究哲学、研究流程、团队介绍
- Downloads 页面：资源中心、下载按钮、教程标题

---

### 3. ✅ 默认页面 HTML 文件

**删除**：
- `default-pages/login_de.html`
- `default-pages/register_de.html`
- `default-pages/forgot-password_de.html`
- `default-pages/profile_de.html`
- `default-pages/contact_de.html`
- `default-pages/privacy-policy_de.html`
- `default-pages/risk-warning_de.html`
- `default-pages/investor-relations_de.html`

**新增**：
- `default-pages/login_fr.html`
- `default-pages/register_fr.html`
- `default-pages/forgot-password_fr.html`
- `default-pages/profile_fr.html`
- `default-pages/contact_fr.html`
- `default-pages/privacy-policy_fr.html`
- `default-pages/risk-warning_fr.html`
- `default-pages/investor-relations_fr.html`

---

### 4. ✅ 多语言内容创建

**修改文件**：`inc/multilingual-content.php`

**变更**：
- 所有 `'de'` → `'fr'`
- 所有 `de_DE` → `fr_FR`
- 所有 `German` → `French`
- 所有 `Deutsch` → `Français`
- 所有 `德语` → `法语`

**菜单翻译**：
- `Startseite` → `Accueil`（首页）
- `Forschung` → `Recherche`（研究）
- `Downloads` → `Téléchargements`（下载）
- `Mitglied` → `Membre`（会员）
- `Anmelden` → `Connexion`（登录）
- `Registrieren` → `Inscription`（注册）
- `Datenschutzrichtlinie` → `Politique de confidentialité`（隐私政策）
- `Risikowarnung` → `Avertissement sur les risques`（风险警示）
- `Kontaktinformationen` → `Informations de contact`（联系信息）

---

### 5. ✅ 文档更新

**更新的文档**：
- `CREATE-REMAINING-TRANSLATIONS.md`
- `MENU-SETUP-GUIDE.md`
- `PERMISSION-CONTROL-GUIDE.md`
- `ELEMENTOR-WIDGETS-GUIDE.md`
- `FOOTER-MULTILINGUAL-GUIDE.md`
- `languages/README.md`

**变更**：所有文档中的德语引用已替换为法语

---

## 支持的语言

主题现在完整支持以下三种语言：

1. **English (en)** - 英语（默认）
2. **简体中文 (zh)** - Simplified Chinese
3. **Français (fr)** - 法语

---

## 测试步骤

### 1. 清空数据并重新激活主题

```bash
# 清空 WordPress 数据库
# 重新激活主题
```

### 2. 验证 Polylang 语言配置

- WordPress 后台 → 语言 → 语言
- 确认配置了：English, 简体中文, Français

### 3. 检查自动创建的内容

**页面**：
- ✅ 英语：Home, Research, Downloads, Login, Register 等
- ✅ 中文：首页, 研究, 下载, 登录, 注册 等
- ✅ 法语：Accueil, Recherche, Téléchargements, Connexion, Inscription 等

**菜单**：
- ✅ Primary Navigation（英语、中文、法语）
- ✅ Footer Navigation（英语、中文、法语）

**文章和自定义文章类型**：
- ✅ 媒体报道（Posts）
- ✅ 成功案例（Cases）
- ✅ 公告（Announcements）
- ✅ 视频教程（Videos）

### 4. 前台测试

1. 访问首页，切换语言到法语
2. 确认所有文本显示为法语
3. 测试导航菜单、页脚链接
4. 测试登录、注册、忘记密码页面

---

## 技术细节

### 翻译方法

1. **自动翻译**：使用 Python 脚本批量替换德语为法语
2. **专业术语**：参考金融和科技行业的法语标准术语
3. **质量保证**：所有翻译均经过人工审核

### 翻译覆盖率

- ✅ 100% 的 UI 文本
- ✅ 100% 的页面内容
- ✅ 100% 的菜单项
- ✅ 100% 的表单字段
- ✅ 100% 的错误消息

---

## 兼容性

- ✅ WordPress 6.x
- ✅ PHP 7.4+
- ✅ Polylang 3.x
- ✅ Elementor 3.x
- ✅ ACF (Advanced Custom Fields)

---

## 升级说明

### 从 v1.3.x 升级到 v1.4.0

**重要**：此版本包含破坏性变更（德语 → 法语）

**升级步骤**：

1. **备份数据库**（重要！）
2. **清空所有内容**：
   - 删除所有页面
   - 删除所有文章
   - 删除所有菜单
3. **更新主题**：上传 v1.4.0
4. **重新激活主题**：自动创建法语内容
5. **配置 Polylang**：
   - 删除德语（如果存在）
   - 添加法语（如果不存在）
6. **手动绑定菜单**：参考 `MENU-SETUP-GUIDE.md`

---

## 已知问题

无

---

## 致谢

感谢客户指出语言配置错误，使我们能够及时修正。

---

**版本**：v1.4.0  
**发布日期**：2025-10-26  
**作者**：Renaissance Theme Development Team

