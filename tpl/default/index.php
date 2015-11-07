<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="description" content="<?=$conf['description']?>">
    <meta name="keywords" content="<?=$conf['keywords']?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=$conf['title']?></title>
    <link rel="shortcut icon" href="<?=$conf['uri']?>/tpl/<?=$conf['tpl']?>/img/favicon.ico">
    <link rel="stylesheet" href="<?=$conf['uri']?>/tpl/<?=$conf['tpl']?>/css/styles.css">
  </head>
  <body>
    <div class="container">
      <div class="row">
        <form>
          <div class="column-5">
            <div class="form-group">
              <div class="form-control" name="input" data-placeholder="Hash or Text" contenteditable="true" spellcheck="false"></div>
            </div>
          </div>
          <div class="column-2 control">
            <div class="form-group">
              <select class="btn">
              </select>
            </div>
            <div class="form-group text-center ext-db">
              <span><label><input type="checkbox" checked> Include external db</label></span>
            </div>
            <div class="form-group">
              <button type="submit" class="btn" name="tohash">Convert to Hash</button>
            </div>
            <div class="form-group">
              <button type="submit" class="btn" name="totext">Convert to Text</button>
            </div>
            <div class="form-group text-center control-counter">
              <span><span name="counter">0</span> hashes in the database</span>
            </div>
            <div class="form-group text-center control-api">
              <span>You can use <a href="https://github.com/ziggi/deHasher/blob/master/README.md#using-external-api">API</a></span>
            </div>
            <div class="form-group text-center control-links">
              <span>
                <a href="http://ziggi.org/">
                  <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                    <path fill="#000000" d="M10,20V14H14V20H19V12H22L12,3L2,12H5V20H10Z" />
                  </svg>
                </a>
              </span>
              <span>
                <a href="https://github.com/ziggi/deHasher">
                  <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                      <path fill="#000000" d="M12,2A10,10 0 0,0 2,12C2,16.42 4.87,20.17 8.84,21.5C9.34,21.58 9.5,21.27 9.5,21C9.5,20.77 9.5,20.14 9.5,19.31C6.73,19.91 6.14,17.97 6.14,17.97C5.68,16.81 5.03,16.5 5.03,16.5C4.12,15.88 5.1,15.9 5.1,15.9C6.1,15.97 6.63,16.93 6.63,16.93C7.5,18.45 8.97,18 9.54,17.76C9.63,17.11 9.89,16.67 10.17,16.42C7.95,16.17 5.62,15.31 5.62,11.5C5.62,10.39 6,9.5 6.65,8.79C6.55,8.54 6.2,7.5 6.75,6.15C6.75,6.15 7.59,5.88 9.5,7.17C10.29,6.95 11.15,6.84 12,6.84C12.85,6.84 13.71,6.95 14.5,7.17C16.41,5.88 17.25,6.15 17.25,6.15C17.8,7.5 17.45,8.54 17.35,8.79C18,9.5 18.38,10.39 18.38,11.5C18.38,15.32 16.04,16.16 13.81,16.41C14.17,16.72 14.5,17.33 14.5,18.26C14.5,19.6 14.5,20.68 14.5,21C14.5,21.27 14.66,21.59 15.17,21.5C19.14,20.16 22,16.42 22,12A10,10 0 0,0 12,2Z" />
                  </svg>
                </a>
              </span>
            </div>
          </div>
          <div class="column-5">
            <div class="form-group">
              <textarea class="form-control" name="output" placeholder="Result" contenteditable="true" readonly></textarea>
            </div>
          </div>
        </form>
      </div>
    </div>
    <script src="<?=$conf['uri']?>/tpl/<?=$conf['tpl']?>/js/scripts.js"></script>
    <script src="<?=$conf['uri']?>/js/scripts.js"></script>
  </body>
</html>
