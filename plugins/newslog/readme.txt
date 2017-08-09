NEWS SYSTEM USAGE
NEWS SYSTEM WIDGETS:
{$news:title} - Displays title of the news item
{$news:teaser} - Displays teaser (short description) of the news item
{$news:tags} - Displays news tags wraped in a links (<a> tags) with filtering functionality
{$news:tagcloud} - Display all tags and filter news by tags in all new's lists
{$news:tagcloud[:newslist_name]} -  Display all tags and filter news by tags in set new's list name
{$news:date[:format]} - Displays date when the news item was created.
                        Can receive an optional parameter format which could be one of the specified here
                        [http://php.net/manual/en/function.date.php]
{$news:preview} - Displays teaser image of the news item
{$news:preview:crop} - Displays cropped teaser
{$news:preview:crop:125x100} - Displays cropped teaser by parameters
{$news:actions} - Admin only widget! Displays controls to edit/delete news item
{$news:url} - Renders news page url
{$news:gplus[:link_title]} - Renders gplus profile link (if specified in settings).
                  If no profile specified widget will render nothing.
                  Accepts an optional parameter link_title which could be any string and
                  will be placed as a gplus link title

MAGICSPACE: NEWSCONTENT
{newscontent} ... {/newscontent} - Newscontent magic space is used to specify place where to display
                                  (and input, if news item created localy)content of the news.
                                  If news item was published via mojo then whole magic space will be replaced
                                  with content received from mojo

Example:

<h3>
  {$news:title}
</h3>
{newscontent}
{$content:newsContent} - just regular content container where to put news item content if news
                          item was created locally {/newscontent}

MAGICSPACE: NEWSLIST
{newslist[:ms_name[:news_items_qty[:order_direction[:tags:tag_to_filter1,tag_to_filter2,...,tag_to_filterN]]]]}
... news list markup here ...
{/newslist}

ms_name - (optional) Name for the newslist. Useful if you're using more then one newslist on one page
news_items_qty - (optional) Amount of news items that will be displayed on a page
order_direction - (optional) could be one of the asc/desc. Inicated how news items will be ordered, ascendant and
                            descedant respectively
tags - (optional) this parameter should come along with tags list, separated by comma. If this parameter specified
        news list will display only news items that have those tags

News list markup inside this magic space will be looped as many times as many news items you have or allowed to display

Example:

Common magic space usage

<ul>
  {newslist:mainList:10:desc}
    <li class="news-list-item">
      {$news:actions}
        <h4>
          <a href="{$news:url}">{$news:title}</a>
        </h4>
          <a class="news-teaser-image" href="{$news:url}">
            <img src="{$news:preview}" alt="news item {$news:title} preview" />
          </a>
          <div class="news-item-date">{$news:date}</div>
          <div class="news-item-description">{$news:teaser}</div>
          <div class="news-item-description">{$news:tags}</div>
    </li>
  {/newslist}
</ul>
List only news with specific tags

<ul>
  {newslist:filtered:tags:sport,music}
    <li class="news-list-item">
      {$news:actions}
      <h4>
        <a href="{$news:url}">{$news:title}</a>
      </h4>
      <a class="news-teaser-image" href="{$news:url}">
        <img src="{$news:preview}" alt="news item {$news:title} preview" />
      </a>
      <div class="news-item-date">{$news:date}</div>
      <div class="news-item-description">{$news:teaser}</div>
      <div class="news-item-description">{$news:tags}</div>
    </li>
  {/newslist}
</ul>

News list widget {$newslist:template_name[:list_name[:per_page[:asc|desc[:pgr[:tags:tag1,tag2, ..., tagN]]]]}
template_name -required parameter, represents news list template that will be loaded for rendering
list_name - optional, unique news list identifier
per_page - optional parameter, how many news items should be displayed in list
asc/desc - order direction. DESC used by default
pgr - use pager or not
tags:tag1,tag2,...,tagN - List of tags to filter a news list