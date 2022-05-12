# in_make
IN句を作成し、文字列で返す

## 説明
```
in_make(string $target, array $in_target, bool $mode = true): string
```

$in_targetが含まれるかを確認する IN句を生成する。

## パラメータ
**$target** 検索する対象
<br><br>

**$in_target** $targetに含まれるかを確認する値が格納された配列
<br><br>

**$mode** 範囲内が有効にするか

falseにすると NOT IN になる

## 戻り値
IN句が書かれた文字列

最後に半角スペースも連結されている

## 例
```
$sql = "SELECT * ";
$sql .= "FROM table ";
$sql .= "WHERE ";
$sql .= in_make("id", [
    1, 2, 3
], false); // idが1, 2, 3以外の物
```