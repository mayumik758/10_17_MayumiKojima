<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="js/jquery-2.1.3.min.js"></script>
    <!-- <script src="js/my.js"></script> -->
    <title>Film Review 新規登録</title>
  </head>
  <body>

    <?php
      session_start();
      // ログインチェック
      include("funcs.php");
      logincheck();
    ?>

    <header>
    <div class="htitle">
    <h1>Film Review</h1>
    <p>Hollywood blockbusters, local greats, European movies, Japanese and Korean dramas, animation, art house films...all the films you watched</p>
    </div>
    </header>

    <main>
      <div class="wrap">
      <a class="alteration" href="select.php">Go Watched Films List</a>
      <div id="tmdbsearch">
          <section class="MovieSection">
          Title<input type="search" name="titlesearch" id="titlesearch">
          Year<input type="search" name="year" id="year">
            <input type="submit" name="button" id="button" value="Search TMDb">
            <ul class="result" id="result"></ul>
          </section>
      </div>

    <script>
      // TMDbのAPIと連携
      $(function () {
        $("#button").click(function () {
        // タイトルとリリース年で検索して映画データを取得
          const result = $("#titlesearch").val();
          const year = $("#year").val();

          $.ajax({
            url:
              "https://api.themoviedb.org/3/search/movie?api_key=",
            data: {
              query: result,
              year: year,
            },
          })
          // 成功したら
          .done(function (data) {
            
            console.log(data);
            // 配列からタイトル、リリース年、平均スコア、ポスター画像を取得して表示
            data.results.forEach(function(filmlist, index){
              const title = filmlist.title;
              const release_date = filmlist.release_date;
              const vote_average = filmlist.vote_average;
              const overview = filmlist.overview;
              const posterPath = `https://image.tmdb.org/t/p/w300/${filmlist.poster_path}`;
              
              const tablerow = `
              <div id="content">
                <div id="images">
                  <img src="${posterPath}">
                </div>
                <div id="texts">
                  <ul>
                    <li><h3 id="stitle">${title}</h3></li>
                    <li id="overview">${overview}</li>
                    <li id="release_date">Release Date: ${release_date}</li>
                    <li>TMDB Score: </li>
                    <li id="score">${vote_average}</li>
                    <li><input type="button" id="selectfilm" value="Select this film"><a href="#content"></a></li>
                  </ul>
                </div>
              </div>`;
              $("#result").append(tablerow);           
              
            });
          })

          // 失敗したら
          .fail(function() {
            console.log("$.ajax failed");
          });
        });

        //「Select this film」ボタンが押された映画のタイトルとTMDbスコア、ポスターのURLを確認
        $(document).on("click", "input[type='button']", function(){
          console.log(this);
          console.log($(this).parent().parent().find("#stitle").text());
          console.log($(this).parent().parent().find("#score").text());

          console.log($(this).parent().parent().parent().parent().find("img").attr("src"));
          // テーブルを表示する
          $("#qs").toggleClass("hidden");
          $("#submit").toggleClass("hidden");

          // 表示された結果から選んだ映画のタイトル、TMDbの平均スコア、画像URLをDBに送信するテーブルに入れる
          $("#title").val($(this).parent().parent().find("#stitle").text()); 
          $("#imdb").val($(this).parent().parent().find("#score").text()); 
          $("#url").val($(this).parent().parent().parent().parent().find("img").attr("src"));
          // スクロールでテーブルに移動
          $('html,body').animate({
            scrollTop : $('#name').offset().top
            }, 'fast');
            return false;

        });

        // リセットボタンで入力を初期化
        $("#reset").on("click", function () {          
          $("#name").val("");
          $("#title").val("");
          $("#date").val("");
          $("#place").val("");
          $("#myscore").val("");
          $("#review").val("");
          $("#titlesearch").val("");
          $("#year").val("");
        });
      });
    </script>
    
      <div id="content">
        <!--DBにPostで送信 -->
        <form method="post" action="insert.php">
          <div id="qs" class="hidden">
            <ul>
              <li>名前<input type="text" name="name" id="name"></li>
              <li>タイトル<input type="text" name="title" id="title" size="60"></li>
              <li></label>観た日<input type="date" name="date" id="date"></li>              
              <li>観た場所<select name="place" id="place"><option value="Cinema">Cinema</option><option value="Amazon Prime">Amazon Prime</option><option value="Netflix">Netflix</option><option value="In-Flight">In-Flight</option><option value="Others">Others</option></select></label><br>
              <li>TMDb Score<input type="number" name="imdb" id="imdb" step="0.1" size="5"></li>
              <li>My Score<input type="number" name="myscore" id="myscore" step="0.1" size="5"></li>
              <textarea name="review" id="review" cols="90" rows="5" placeholder="My Review"></textarea><br>
              <li><input type="url" name="url" id="url" size="90" placeholder="Image URL"></li>
            </ul>
          </div>
          <div id="submit" class="hidden">
              <input type="reset" id="reset" value="Reset">
              <input type="submit" id="add" value="Add">
          </div>
        </form>
      </div>
    </div>
    </main>
    <footer></footer>
  </body>
  
</html>

