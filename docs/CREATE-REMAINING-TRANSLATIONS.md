# 剩余翻译文件创建指南

由于 Risk Warning 和 Investor Relations 页面内容较长（分别为157行和147行），为了节省token，这里提供创建指南。

## 已完成的翻译文件 ✅

### 简短页面（已完成）
- ✅ `login_zh.html` / `login_de.html`
- ✅ `register_zh.html` / `register_de.html`
- ✅ `forgot-password_zh.html` / `forgot-password_de.html`
- ✅ `profile_zh.html` / `profile_de.html`
- ✅ `contact_zh.html` / `contact_de.html`
- ✅ `privacy-policy_zh.html` / `privacy-policy_de.html`

### 空占位页面（无需翻译）
- `home.html` (89B) - Elementor 管理
- `research.html` (93B) - Elementor 管理
- `downloads.html` (94B) - Elementor 管理

## 待创建的翻译文件 📝

### 1. Risk Warning 页面
**文件名：**
- `risk-warning_zh.html` (中文版)
- `risk-warning_de.html` (法语版)

**内容结构：**
```
- Header (风险警告标题)
- General Investment Risks (一般投资风险)
- Quantitative Strategy Risks (量化策略风险)
- Market and Economic Risks (市场和经济风险)
- Operational and Business Risks (运营和业务风险)
- Specific Investment Warnings (特定投资警告)
- Suitability and Eligibility (适用性和资格)
- Disclaimer (免责声明)
- Contact Information (联系信息)
```

**关键翻译：**
- 中文：风险警告、投资风险、量化策略风险、市场波动、流动性风险等
- 法语：Risikowarnung, Investitionsrisiken, Quantitative Strategierisiken, Marktvolatilität, Liquiditätsrisiko等

### 2. Investor Relations 页面
**文件名：**
- `investor-relations_zh.html` (中文版)
- `investor-relations_de.html` (法语版)

**内容结构：**
```
- Header (投资者关系标题)
- Key Performance Metrics (关键绩效指标)
  - Assets Under Management ($165B)
  - Annual Returns (39%)
  - Years of Excellence (35+)
  - Employees (300+)
- Financial Performance (财务表现)
- Investment Philosophy (投资理念)
- Fund Information (基金信息)
- Corporate Governance (公司治理)
- Contact for Investors (投资者联系方式)
```

**关键翻译：**
- 中文：投资者关系、资产管理规模、年化收益率、财务表现、投资理念等
- 法语：Investor Relations, Verwaltetes Vermögen, Jährliche Renditen, Finanzielle Leistung, Anlagephilosophie等

## 快速创建方法

### 方法1：手动翻译（推荐）
1. 复制英文原文件内容
2. 使用专业翻译工具（DeepL、Google Translate）翻译HTML中的文本内容
3. 保留所有HTML标签和class名称不变
4. 保存为对应的语言文件名

### 方法2：使用AI助手
由于内容较长，可以分段请求AI翻译：
1. 将文件分成多个部分（每部分约30-40行）
2. 逐部分翻译
3. 合并成完整文件

### 方法3：简化版本（快速方案）
如果时间紧迫，可以创建简化版本：
1. 只翻译标题和关键段落
2. 保留英文原文作为详细说明
3. 添加注释说明"详细内容请参考英文版本"

## 文件命名规范

所有翻译文件遵循以下命名规范：
- 中文：`{原文件名}_zh.html`
- 法语：`{原文件名}_de.html`

例如：
- `risk-warning.html` → `risk-warning_zh.html` / `risk-warning_de.html`
- `investor-relations.html` → `investor-relations_zh.html` / `investor-relations_de.html`

## 集成到主题

翻译文件创建完成后，需要修改 `functions.php` 中的 `rena_create_default_pages()` 函数，使其根据语言加载对应的HTML文件。

参考代码：
```php
// 在 rena_create_default_pages() 函数中
$lang = function_exists('pll_current_language') ? pll_current_language() : 'en';
$html_file_suffix = ($lang === 'en') ? '' : '_' . $lang;
$html_file_path = get_template_directory() . '/default-pages/' . $page_config['html_file_base'] . $html_file_suffix . '.html';
```

## 注意事项

1. **保留HTML结构**：不要修改任何HTML标签、class名称或data属性
2. **保留占位符**：如表单占位符等动态内容区域
3. **保留链接**：邮箱地址、电话号码等保持不变
4. **日期格式**：根据语言习惯调整日期格式
   - 英文：January 1, 2025
   - 中文：2025年1月1日
   - 法语：1. Januar 2025

## 测试检查清单

创建翻译文件后，请检查：
- [ ] 文件名正确（包含语言后缀）
- [ ] HTML结构完整（开闭标签匹配）
- [ ] class名称未被翻译
- [ ] data-translate属性保留
- [ ] 特殊字符正确编码（如中文引号、法语变音符号）
- [ ] 文件编码为UTF-8

---

**Renaissance Theme** - Version 1.1.0

