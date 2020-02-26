# Views Work plugin for Craft CMS 3.x

---
Please view the full documentation at [io.24hoursmedia.com](https://io.24hoursmedia.com/views-work)!

----

* Track pageviews by day, week, month or grand total
* View popular entries in a widget
* Get popular entries and pageviews on the front-end in twig
* Uses a signed tracking image to register only real page views



## Usage

Show a beacon 1px x 1px image to register a pageview:

```
{# render the image for registration #}
{{ entry | views_work_image }}
```

Get popular items:
```
{% set query = craft.entries.section('..').limit(5) %}
{% do craft.views_work.sortPopular(query) %}
{% set entries = query.all

# sort popular items by week, monthly or daily views
{% do viewsWork.sortPopular(query, 'total') %}
{% do viewsWork.sortPopular(query, 'week') %}
{% do viewsWork.sortPopular(query, 'month') %}
{% do viewsWork.sortPopular(query, 'day') %}

# exclude items with 0 views
{% do viewsWork.sortPopular(query, 'week', {min_views: 1}) %}

# only deliver items with more than 100 views
{% do viewsWork.sortPopular(query, 'week', {min_views: 100}) %}
```

### Cron job

Execute this cron at an approprate time, i.e. once every day at 00:01 pm.  
It resets the daily, weekly and monthly view counters.  

The cron checks wether it is the first day of the week or month before resetting monthly or weekly views.

    ./craft views-work/default/reset-views

----

Brought to you by [24hoursmedia](https://www.24hoursmedia.com)
