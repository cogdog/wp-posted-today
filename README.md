# Posted Today
** by [cog.dog](https://cog.dog)

-----
*If this kind of stuff has any value to you, please consider supporting me so I can do more!*

[![Support me on Patreon](http://cogdog.github.io/images/badge-patreon.png)](https://patreon.com/cogdog) [![Support me on via PayPal](http://cogdog.github.io/images/badge-paypal.png)](https://paypal.me/cogdog)

----- 

Provides a `[postedtoday]` shortcode that generates a list of posts from previous year on the same month and day as today. Shortcode options let you also set the display for a specific month/day.


## See it in Action

* https://cogdogblog.com/this-day/ and https://cogdogblog.com/this-day/jan-15/
* https://budtheteacher.com/blog/posted-today/
* https://coyotechalk.com/this-day/
* http://collect.readwriterespond.com/otd/
* https://blog.ohheybrian.com/otd/
* http://johnjohnston.info/blog/on-this-day/
* https://mrkapowski.com/sitemap


## Installation

1. Upload `wp-posted-today` folder to the `/wp-content/plugins/` directory (or upload via Plugins the zip file of this repository
2. Activate the plugin through the 'Plugins' menu in WordPress

## Using Shortcode
Use the `[postedtoday]` shortcode  in any page to create a list by year of posts on the current day, includes a post excerpt

For an arbitrary  day, include a `month=` and `day=` parameter, e.g. `[postedtoday month="4" day="1"]`

Hide the post excerpts via `[postedtoday excerpt="0"]`

## CSS 

Modify output with custom CSS.

Output looks like:

    <ul class="todaypost">
   	  <li><strong>Month Day, Year</strong>
   		 <ul>
   			<li>
   			    <a href="http://blog.blog/blah">Post Title</a> 
   			      <span class="today_excerpt">excerpt excerpt excerpt excerpt</span>
   			  </li>
   		 </ul>
   	 </li>
   	 </ul>

Possibilities for CSS include:

Hide bullets for the year list items
     ul.todaypost > li {
        list-style-type: none;
     }

Reduce line height for posts, add margin to space them out
     ul.todaypost > li > ul > li {
         line-height: 1.0;
         margin-bottom: 1em;
     }

Smaller excerpt size, move below title by making it a block display
    span.today_excerpt {
       font-size: 0.8em;
       display: block;
     }


## Changes

* v0.1 Just got it working!
* v0.2 Adjusted the sentence announcing the number of posts to be grammatically correct for a result of 1
* v0.3 Refined call to get current year in loop
* v0.4 Date fixes, html cleanup, output set for langauage translation





