# 翻译文件说明

本目录包含 Renaissance 主题的多语言翻译文件。

## 文件列表

- `zh_CN.po` / `zh_CN.mo` - 简体中文翻译
- `en_US.po` / `en_US.mo` - 英语（美国）翻译
- `renaissance.pot` - 翻译模板文件

## 文件类型说明

- **`.po` 文件**：可编辑的翻译源文件（文本格式）
- **`.mo` 文件**：编译后的翻译文件（二进制格式，WordPress 实际使用的文件）
- **`.pot` 文件**：翻译模板，包含所有需要翻译的字符串

## 如何使用

### 自动加载

主题会自动加载对应语言的翻译文件：

- 当 WordPress 后台语言设置为"简体中文"时，会加载 `zh_CN.mo`
- 当 WordPress 后台语言设置为"English (United States)"时，会加载 `en_US.mo`

### 切换语言

1. 进入 `后台 → 设置 → 常规`
2. 找到"站点语言"选项
3. 选择您需要的语言
4. 点击"保存更改"
5. 刷新页面即可看到翻译生效

## 如何编辑翻译

### 方法一：使用 Poedit 编辑器（推荐）

1. 下载并安装 [Poedit](https://poedit.net/)
2. 用 Poedit 打开 `.po` 文件（如 `zh_CN.po`）
3. 编辑翻译内容
4. 保存时会自动生成对应的 `.mo` 文件

### 方法二：手动编辑

1. 用文本编辑器打开 `.po` 文件
2. 找到需要修改的 `msgid` 和 `msgstr`
3. 修改 `msgstr` 后面的翻译内容
4. 保存文件
5. 在终端运行命令编译：
   ```bash
   cd /path/to/theme/languages
   msgfmt zh_CN.po -o zh_CN.mo
   ```

## 翻译文件格式示例

```po
# 原文
msgid "Cases"
# 翻译
msgstr "案例"

msgid "Add New Case"
msgstr "添加新案例"
```

## 已翻译的内容

### 自定义文章类型
- Cases（案例）
- Announcements（公告）
- Videos（视频教程）

### 页面元素
- 导航菜单
- 案例详情页
- 视频详情页
- Downloads 页面
- 页脚链接

### 通用文本
- 搜索、分页、加载等

## 添加新语言

如果需要添加其他语言（如法语、日语等）：

1. 复制 `renaissance.pot` 或 `en_US.po` 文件
2. 重命名为对应的语言代码（如 `fr_FR.po`、`ja.po`）
3. 用 Poedit 打开并翻译所有条目
4. 保存后会自动生成 `.mo` 文件
5. 上传到 `languages/` 目录

### 常用语言代码

| 语言 | 代码 | 文件名 |
|-----|------|--------|
| 简体中文 | zh_CN | zh_CN.po / zh_CN.mo |
| 繁体中文 | zh_TW | zh_TW.po / zh_TW.mo |
| 英语（美国） | en_US | en_US.po / en_US.mo |
| 英语（英国） | en_GB | en_GB.po / en_GB.mo |
| 法语 | fr_FR | fr_FR.po / fr_FR.mo |
| 法语 | fr_FR | fr_FR.po / fr_FR.mo |
| 日语 | ja | ja.po / ja.mo |
| 韩语 | ko_KR | ko_KR.po / ko_KR.mo |
| 西班牙语 | es_ES | es_ES.po / es_ES.mo |

## 故障排除

### 翻译不生效？

1. 确认 `.mo` 文件存在且文件名正确
2. 检查 WordPress 后台语言设置
3. 清空浏览器缓存
4. 清空 WordPress 缓存（如果使用了缓存插件）

### 某些文本没有翻译？

1. 检查 `.po` 文件中是否包含该字符串的 `msgid`
2. 确认 `msgstr` 不为空
3. 重新编译 `.mo` 文件

### 编译错误？

确保系统已安装 `gettext` 工具：

```bash
# Ubuntu/Debian
sudo apt-get install gettext

# macOS
brew install gettext

# 验证安装
msgfmt --version
```

## 技术支持

如需帮助或发现翻译错误，请联系主题开发团队。

