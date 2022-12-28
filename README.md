<p align="center"><img src="preview.jpg?v=3" /></p>
<h1 align="center">language-Taiwan</h1>
<p align="center">WonderCMS 中文化外掛</p>

<br><br>

此外掛是 WonderCMS 的設定頁面中文化，基於 `https://github.com/StephanStanisic/zlanguage-nl_NL` 修改。

外掛作者 : carbeso

# 已知問題
此外掛僅能翻譯設定裡的項目，其他由程式產生(新建頁面的標題、關鍵字與敘述)與Alert訊息(訊息本文在index.php裡)無法翻譯。

# 如何使用
1. 登入你的 WonderCMS 管理者後台
2. 點擊 "Settings" 再點擊 "Plugins"
3. 再列表中找到這個外掛再點擊 "install"

# 如何製作其語言他翻譯

1. 分叉這個儲存庫 (這是包含所有翻譯的最新版本) : https://github.com/robiso/translation-slovenian/
2. 重新命名儲存庫 "translation-yourlanguage"。 (參考這裡如何重新命名 : https://help.github.com/en/github/administering-a-repository/renaming-a-repository)
3. 重新命名 `translation-slovenian.php` 成 "translation-yourlanguage.php" (將 `yourlanguage` 改成你的語言)。
4. 確認第二點的儲存庫名稱與第三點的PHP檔案名稱相一致。
5. 直接在 GitHub 上修改 SVG 檔 (或使用其他編輯器) : https://github.com/robiso/translation-slovenian/edit/master/language-solid.svg
    - 第78行，將 "Slovenian" 改成你的語言。
6. 重新命名 `sl.csv` 成你的地區代碼。 (地區代碼參考 https://www.science.co.il/language/Locale-codes.php)
7. 翻譯這個 csv 檔。
8. 更新簡介檔(summary)。
9. 建立拉請求(pull request)到外掛列表。 https://github.com/robiso/wondercms-cdn-files 

## 這是如何運作的
所有的翻譯詞都在 CSV 檔裡。為什麼是 CSV 檔? 大部分主流的 CMS 也都是這麼做的，這麼做也是跟上主流。

你會看到很多像是這樣 :

```
SECTION NAME
	"> Some text","> Your translation"
```

“解析器”會跳過所有不等於兩個值的行，因此會跳過 SECTION NAME（因為只有一個值）。
所有配對都採用 `preg_replace` 正則表達式格式。就是這麼簡單。 `>` 和 `> ` 的前置確保我們只匹配標籤的內容。

例如 `> Security` 只會配對 
```
<a href="#security" aria-controls="security" role="tab" data-toggle="tab" class="nav-link">Security</a>
```
而不會配對
```
<div class="btn-group w-50"><button type="submit" class="btn btn-success" name="betterSecurity" value="on">ON (warning: may break your website)</button></div>
<div class="btn-group w-50"><button type="submit" class="btn btn-danger" name="betterSecurity" value="off">OFF (reset htaccess to default)</button></div>
```
