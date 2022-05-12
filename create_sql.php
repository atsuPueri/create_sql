<?php

/**
 * order by句作成
 * @param array $order_array （例）[id=>'ASC', price=>'DESC]
 * 
 * @return string order by句 （例）'ORDER BY id ASC , price DESC '
 * 
 * @throws Exception
 */
function order_by_make(array $order_array = []): string
{
    // 空なら空文字
    if (count($order_array) === 0) {
        return '';
    }
    

    // 中身を全て取得
    $order_set = [];
    foreach ($order_array as $key => $value) {
        if ('ASC' !== $value && 'DESC' !==$value) {
            $value = strtoupper($value);

            if ('ASC' !== $value && 'DESC' !==$value) {
                throw new Exception(">> ASC もしくは DESC じゃありません。");
                return '';
            }
        }
        $order_set[] = $key . ' ' . $value;
    }

    // 中身を全て結合
    $order_implode = implode(' , ',  $order_set);


    // order by句作成
    $order_by = 'ORDER BY ';
    $order_by .= $order_implode . ' ';

    return $order_by;
}

/**
 * INTO句作成
 * @param string $table 追加するテーブル名
 * @param array $associative_array 追加する値の連想配列
 * 
 * 
 * 
 * ## 指定の仕方
 * -------------------------
 * 'カラム名' => 値
 * 
 * ### 対応している型
 * 
 * - string
 * - int
 * - bool
 * - null
 * -------------------------
 * 
 * ## 入力例
 * 
 * [
 * 
 * &nbsp;&nbsp;&nbsp;&nbsp; "id" => 123,
 * 
 * &nbsp;&nbsp;&nbsp;&nbsp; "age" => "4",
 * 
 * &nbsp;&nbsp;&nbsp;&nbsp; "sold_out" => true
 * 
 * ]
 * 
 * @param bool $mode 型を認識し、$associative_arrayの文字列にシングルクォーテーションを付けるか
 * 
 * @return string （例） 'INSERT INTO table (id, age) VALUES ('123', '4') '
 */
function into_make(string $table, array $associative_array, bool $mode = true): string
{
    // insert文作成
    $keys = [];
    $values = [];
    foreach ($associative_array as $key => $value) { // 添え字と中身をそれぞれ配列に格納
        $keys[] = $key;

        // 文字列が渡されたときはシングルで囲いそれ以外の時は囲わない
        if ($mode) {
            if (is_string($value)) {
                $values[] = "'" . $value . "'";
            } else if (is_int($value)) {
                $values[] = (string)$value;
            } else if (is_bool($value)) {
                $values[] = $value ? "true" : "false";
            } else if (is_null($value)) {
                $values[] = "null";
            }
        } else {
            $values[] = $value;
        }
    }


    $column = implode(", ", $keys); // A,B,C 　この形で格納
    $data = implode(", ", $values); // 'A','B','C','D' 
    $sql = "INTO " . $table . " (" . $column . ") VALUES (" . $data . ") ";

    return $sql;
}

/**
 * FROM句作成(JOIN専用)
 * 
 * @param array $abbreviation_array
 * テーブル名の省略名を格納する
 * 
 * ## 入力例
 * 
 * [
 * 
 * &nbsp;&nbsp;&nbsp;&nbsp; 'products' => 'p',
 * 
 * &nbsp;&nbsp;&nbsp;&nbsp; 'exhibit' => 'e',
 * 
 * &nbsp;&nbsp;&nbsp;&nbsp; 'community' => 'c',
 * 
 * &nbsp;&nbsp;&nbsp;&nbsp; 'director' => 'd',
 * 
 * ]
 * 
 * ---------------------------------------------------
 * 
 * 
 * @param array $match **結合条件を配列で格納する**
 * 
 * mainに結合元を格納する 
 * 
 * ### 対応している結合方法
 * ---------------------------------------------------
 * 
 * - __I ... INNER JOIN
 * 
 * - __R ... RIGHT OUTER JOIN
 * 
 * - __L ... LEFT OUTER JOIN
 * 
 * ※ 結合方法は添え字0番目じゃなくても問題なく作動する
 * 
 * ---------------------------------------------------
 * 
 * 
 * ## 入力例
 * ---------------------------------------------------
 * 
 * [
 * 
 * &nbsp;&nbsp;&nbsp;&nbsp; 'main' => 'p',
 * 
 * &nbsp;&nbsp;&nbsp;&nbsp; [ "__I", "p" => "id", "e" => "id", ],
 * 
 * &nbsp;&nbsp;&nbsp;&nbsp; [ "__R", "p" => "id", "c" => "id", ],
 * 
 * &nbsp;&nbsp;&nbsp;&nbsp;[ "__I", "c" => "name", "d" => "name", ],
 * 
 * ]
 * 
 * @return string 例
 * 
 * "FROM products as p INNER JOIN exhibit as e ON p.id = e.id "
 * 
 * @throws Exception
 */
function from_join_make(array $abbreviation_array, array $match): string
{
    // 既に使われた省略語
    $already_used_abbreviation_array = [];


    if (!isset($match['main'])) {
        throw new Exception(">> エラー:mainが宣言されていません");
        return '';
    }

    // sql作成
    $sql = '';
    $sql .= 'FROM ' . array_search($match['main'], $abbreviation_array) . ' as ' . $match['main'] . ' ';

    // mainが使われた事を格納する
    $already_used_abbreviation_array[] = $match['main'];

    // 全てを確認して結合していく
    foreach ($match as $key => $joing_array) {

        // mainは使わないから次に飛ばす
        if ($key === 'main') {
            continue;
        }


        // 確認情報に__I等が含まれているか
        if (in_array('__I', $joing_array, true)) {
            $join_type = 'INNER JOIN';
        } else if (in_array('__R', $joing_array, true)) { // __Rが含まれているか
            $join_type = 'RIGHT OUTER JOIN';
        } else if (in_array('__L', $joing_array, true)) { // __Lが含まれているか
            $join_type = 'LEFT OUTER JOIN';
        } else {
            throw new Exception('>> エラー:配列には\'__I\'か\'__R\'か\'__L\'を含める必要があります。');
            return '';
        }


        // 一つの結合情報を見る
        foreach ($joing_array as $joing_key => $joing_information) {

            // 添え字が
            if ($joing_information === '__I' || $joing_information === '__R' || $joing_information === '__L') {
                continue;
            } else if (is_numeric($joing_key)) {
                throw new Exception(">> エラー:数値の省略カラム名に数値は使用できません");
                return '';
            } else if (!in_array($joing_key, $abbreviation_array)) { // 添え字(省略カラム名)が宣言されていない時
                throw new Exception(">> エラー:" . $joing_key . 'という省略カラム名が宣言されていません。');
                return '';
            }

            // 省略添え字がまだ使われていないとき
            if (!in_array($joing_key, $already_used_abbreviation_array)) {

                // joing_keyを使われたことを格納する
                $already_used_abbreviation_array[] = $joing_key;

                // joing_keyを定義した添え字を格納
                $column = array_search($joing_key, $abbreviation_array);


                // JOIN句を書くときは基本略語を宣言するから
                $sql .= $join_type . ' ' . $column . ' as ' . $joing_key . ' ';
                $sql .= 'ON ' . $joing_key . '.' . $joing_information . ' = ';
            } else {
                $on_save = $joing_key . '.' . $joing_information;
            }
        }
        $sql .= $on_save . ' ';
    }

    return $sql;
}

/**
 * limit句を作成
 * @param int $offset 開始位置
 * @param int $length 長さ
 * @return string （例）"LIMIT 2, 5 "
 * 
 * 開始位置と長さが正しく入力されない場合は半角スペースだけが返される
 */
function limit_make(int $offset, int $length): string
{
    if (!is_int($offset) || !is_int($length)) {
        throw new Exception(">> エラー:引数に数値以外が渡されています。");
        return '';
    }

    $limit = 'LIMIT ';
    $limit .= $offset;
    $limit .= ', ';
    $limit .= $length . ' ';

    return $limit;
}

/**
 * between句を作成
 * @param string $target 検索対象
 * @param string $start 開始位置
 * @param string $end 終了位置
 * @param bool $mode 範囲内が有効(NOT)か
 * @return string 例 "id BETWEEN 5 AND 10 "
 * 
 * ```
 * between_make("id", 5, 10, true); //idが5~10
 * ```
 */
function between_make(string $target, string $start, string $end, bool $mode = true): string
{

    $between = $target . ' ';

    $between .= $mode ? '' : 'NOT ';

    $between .= 'BETWEEN ';
    $between .= $start . ' ';
    $between .= 'AND ';
    $between .= $end . ' ';

    return $between;
}

/**
 * in句を作成
 * @param string $target 対象
 * @param array $in_target 含む対象
 * 
 * 例
 * 
 * ["apple", "grape", "orange"]
 * 
 * @param bool $mode in_targetが有効か
 * @return string 例 "IN ('apple', 'grape', 'orange') "
 * 
 */
function in_make(string $target, array $in_target, bool $mode = true): string
{

    $in = $target  . ' ';
    $in .= $mode ? 'IN ' : 'NOT IN ';
    $in .= '(';
    $in .= '\'' . implode(', ', $in_target) . '\'';
    $in .= ')';

    return $in;
}
