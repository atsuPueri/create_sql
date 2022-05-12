# order_by_make
ORDER BY句を作成し、文字列で返す

## 説明
```
order_by_make(array $order_array = []): string
```
複数のORDER BYに対応できるようになっている。

添え字にソートしたいカラム名 => ソート方法

使用可能なソート方法は次のとおりです。

- ASC
- DESC

大文字小文字は区別しません。

## パラメータ
**$order_array** ソート順

前に記述したものが優先される
<br><br>

## 戻り値
ORDER BYが書かれた文字列 複数のソートも対応されている

## 例
```
$sql = "SELECT * ";
$sql .= "FROM table ";
$sql .= order_by_make([
    "id" => "ASC",
    "age" => "DESC"
]);
```