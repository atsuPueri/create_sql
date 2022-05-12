# between_make
BETWEEN句を作成し、文字列で返す

## 説明
```
between_make(string $target, string $start, string $end, bool $mode = true): string
```
範囲検索をする BETWEEN句 を生成する。

## パラメータ
**$target** 検索する対象
<br><br>

**$start** 開始位置
<br><br>

**$end** 終了位置
<br><br>

**$mode** 範囲内が有効にするか

falseにすると NOT BETWEEN になる
<br><br>

## 戻り値
BETWEEN句が書かれた文字列

最後に半角スペースも連結されている

## 例
```
$sql = "SELECT * ";
$sql .= "FROM table ";
$sql .= "WHERE ";
$sq; .= between_make("id", 5, 20, false); // idが5~20以外
```