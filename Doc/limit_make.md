# limit_make
LIMIT句を作成し、文字列で返す

## 説明
```
limit_make(int $offset, int $length): string
```
$offsetからlengthの分を取得するLIMIT句を生成する。

## パラメータ
**$offset** 開始位置
<br><br>

**$length** 長さ
<br><br>

## 戻り値
LIMIT句が書かれた文字列

最後に半角スペースも連結されている

## 例
```
$sql = "SELECT * ";
$sql .= "FROM table ";
$sql .= limit_make;
```