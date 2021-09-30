<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>mission3-5</title>
    </head>
    <body>
        

        
        <?php


        //日時データの取得
        $time= date("Y年m月d日 H:i:s");
        
        //ファイルの指定
        $filename="mission_3-5.txt";
        
        //1行1要素で格納
        $lines=file($filename,FILE_IGNORE_NEW_LINES);
        
        //データの格納
        $hnum="";
        $ename="";
        $ecomment="";
        $editpass="";
        
        //投稿機能
        //フォーム内が空でない場合に以下を実行する
        if (!empty($_POST['name']) && !empty($_POST['comment']) && !empty($_POST["pass"])) {
            
            //入力データの受け取り&変数に代入
            $name = $_POST['name'];
            $comment = $_POST['comment'];
            $pass = $_POST["pass"];
            
            // hiddenが空のときは新規投稿、空でない場合は編集 ⇒ここで判断
            //hiddenが空の時＝新規投稿
            if (empty($_POST['hidden'])) {
                
                // 以下、新規投稿機能
                //ファイルの存在がある場合は投稿番号+1、なかったら1を指定する
                if (file_exists($filename)) {
                    $num = count(file($filename)) + 1;
                }
                
                //書き込む文字列を組み合わせて変数に代入
                $data = $num . "<>" . $name . "<>" . $comment . "<>" . $time . "<>". $pass ."<>" ;
                    
                //追記モードでファイルを開く
                $fp = fopen($filename,'a'); 
                    
                //データと改行をファイルへ書き込み
                fwrite($fp, $data.PHP_EOL);
                echo "<br>投稿しました。<br>";
                //ファイルを閉じる
                fclose($fp);
                
                
            //hiddenが空でないとき(編集内容の投稿)
            } else {
                // 以下編集機能
                //入力データの受け取り＆変数に代入
                $hidden = $_POST['hidden'];
                
                //読み込んだファイルの中身を配列に格納する
                //Wでファイルを開けると中身が消えるため(？)
                $ret_array = file($filename);
                
                //ファイルを書き込みモードでオープン
                $fp = fopen($filename, "w");
                
                //変数の初期化
                $text=[];
                
                //配列の数だけループさせる
                foreach ($ret_array as $line) {
                    
                    //explode関数でそれぞれの値を取得
                    $text = explode("<>", $line);
                    
                    //投稿番号と編集番号が一致したら
                    if ($text[0] == $hidden) {
                        
                        //フォームから新たに送信された値と差し替えて上書き
                        fwrite($fp, $hidden."<>".$name."<>".$comment."<>".$time.PHP_EOL);
                        echo "<br>投稿を編集しました。<br>";
                    } else {
                        //一致しなかったところはそのまま書き込む
                        fwrite($fp, $line);
                    }
                }
            fclose($fp);
            }
            
        }

      
//-------------------------削除-----------------------------
            //定義づけ
            if (!empty($_POST["delete"])||!empty($_POST["dpass"])) {
                
                //変数に格納
                $delete = $_POST["dnum"];
                $dpass = $_POST["dpass"];
        
                //ファイル全体を変数に格納 
                //※ファイルをwで開くとデータが消えて投稿番号が取得できなくなるため
                $delCon = file("mission_3-5.txt");
                
                //ファイルの中身を消してオープン
                $fp = fopen("mission_3-5.txt", "w");
        
                //変数の初期化
                $text=[];
                
                //for(初期値;条件;増減式){
                //for( 0 ; ファイルの行数まで; 加算)
                for ($j = 0; $j < count($delCon); $j++) {
        
                    //要素の分解
                    $text = explode("<>", $delCon[$j]);
                    //パスワードが正しいとき
                    if ($text[4]==$dpass) {
                        //番号が一致しないとき
                        if ($text[0] <> $delete) {
                            
                            //元のデータの書き込み
                            fwrite($fp, $delCon[$j]);
                        }else{
                            fwrite($fp,"<>"."<>"."<>".PHP_EOL);
                            echo "<br>投稿を削除しました。<br>";
                        }
                    }else{
                        //全データの書き込み
                        fwrite($fp,$delCon[$j]);
                    }
                    
                }
                //ファイルを閉じる
                fclose($fp);
            }
        
//-------------------------編集-----------------------------
//既存投稿フォームに編集番号に対応する名前とコメントを表示する
        //2.【POST送信で「編集対象番号」を送信。
        //受信する際は、if文で既存の各フォームとはまた別に「編集フォーム」で処理を分岐させる
        
        if(!empty($_POST["editnum"])||!empty($_POST["epass"])){
            
            //番号の受け取り＆変数に代入
            $enum=$_POST["enum"];
            $epass=$_POST["epass"];
            
            //3.【ファイル読み込み関数で、ファイルを開く
            $fp=fopen($filename,"r");
            
            //ファイルがある場合
            if(file_exists($filename)){
                
            //変数の初期化
            $text=[];
            
            //ファイルの中身を1行1要素として配列変数に代入
            //3.ファイルを開き先ほどの配列の要素数（＝行数）だけループさせる】
                foreach($lines as $line){
            
                // ループ処理内：区切り文字「<>」で分割して、投稿番号を取得】
                    //文字列を分割
                    $text=explode("<>",$line);
            
                    //パスワードが正しいとき
                    if($text[4]==$epass){
                        //投稿番号と編集対象番号を比較
                        if($text[0]==$enum){
                
                            //イコールの場合はその投稿の名前、コメント、パスワードを取得
                            $hnum=$text[0];
                            $ename=$text[1];
                            $ecomment=$text[2];
                            $editpass=$text[4];
                            
                            echo "<br>投稿を編集します。<br>";
                        }
                    }
                }
            }
        }

        ?>
        
         <!--入力フォーム-->
        <form method="post" action="">
            <!--新規投稿か判断するための、編集番号取得フォーム-->
            <input type="hidden" name="hidden" value="<?php echo $hnum;?>"><br>
            
            
            <h4>投稿</h4>
            <!--名前・コメント入力フォーム-->
            <!--※※※編集操作時「名前」と「コメント」の内容が既に入っている状態で表示させる】-->
            <!--名前入力フォーム-->
            <input type="text" name="name" placeholder="<?php echo $ename;?>">
            <!--コメント入力フォーム-->
            <input type="text" name="comment" placeholder="<?php echo $ecomment;?>">
            <input type="password" name="pass" placeholder="<?php echo $editpass;?>">
            <!--提出ボタン-->
            <button type="submit" name="submit">送信</button><br><br>
            
            
            <h4>削除</h4>
            <!--削除対象番号入力フォーム-->
            <input type="num" name="dnum" placeholder="削除番号">
            <input type="password" name="dpass">
            <!--削除ボタン-->
            <button type="submit" name="delete">削除</button><br><br>
            
            
            <h4>編集</h4>
            <!--編集番号入力フォーム-->
            <input type="text" name="enum" placeholder="編集番号">
            <input type="password" name="epass">
            <!--編集ボタン-->
            <button type="submit" name="editnum">編集</button> 
        
        </form>
        
        <?php
            /* ブラウザ反映*/ 
            // fopenでファイルを開く（'r'読み込みモードで開きPHPファイルに表示）
            $fp = fopen($filename, 'r');
            //ファイルの存在を確認
            if(file_exists($filename)){
                $lines=file($filename,FILE_IGNORE_NEW_LINES);
                //ループ処理
                foreach ($lines as $line){
                    //文字列を分割
                    $text=explode("<>",$line);
                    //分割した文字列を格納
                    $str=($text[0].$text[1].$text[2].$text[3]);
                        //表示
                        echo $str."<br>";
                    }
                // fcloseでファイルを閉じる
                fclose($fp);
            }

        ?>
    </body>
</html>