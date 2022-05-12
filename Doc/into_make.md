# into_make
INTO句とVALUESを作成し、文字列で返す。

## 説明
```
into_make(string $table, array $associative_array, bool $mode = true): string
```
INSERT 句と一緒に使うINTO VALUESを生成
$tableに追加先のテーブルを指定し、$associative_arrayに追加したい内容を 'カラム名'=>値 で記述する。

## パラメータ
**$table** 追加先のテーブル名
<br><br>

**$associative_array** 追加内容の連想配列
<br><br>

**$mode** 型を認識し、$associative_arrayの文字列にシングルクォーテーションを付けるか

### 対応している型
 - string
 - int
 - bool
 - null
 <br><br>


## 戻り値
INTO句が書かれた文字列
最後に半角スペースも連結されている

## 例
```
$sql = "INSERT ";
$sql .= into_make("table", [
    "id" => 123,
    "name" => "user",
    "stock" => null,
], false);
$sql .= into_make("product", [
    "id" => 55,
    "name" => "apple",
    "stock" => 10,
]);
```
