
# The Timber Starter Theme

The "_s" for Timber: a dead-simple theme that you can build from. The primary purpose of this theme is to provide a file structure rather than a framework for markup or styles. Configure your Sass, scripts, and task runners however you would like!

[![Build Status](https://travis-ci.org/timber/starter-theme.svg)](https://travis-ci.org/timber/starter-theme)

## Installing the Theme

Install this theme as you would any other, and be sure the Timber plugin is activated. But hey, let's break it down into some bullets:

1. Make sure you have installed the plugin for the [Timber Library](https://wordpress.org/plugins/timber-library/) (and Advanced Custom Fields - they [play quite nicely](https://timber.github.io/docs/guides/acf-cookbook/#nav) together). 
2. Download the zip for this theme (or clone it) and move it to `wp-content/themes` in your WordPress installation. 
3. Rename the folder to something that makes sense for your website (generally no spaces and all lowercase). You could keep the name `timber-starter-theme` but the point of a starter theme is to make it your own!
4. Activate the theme in Appearance >  Themes.
5. Do your thing! And read [the docs](https://github.com/jarednova/timber/wiki).

## What's here?

`static/` is where you can keep your static front-end scripts, styles, or images. In other words, your Sass files, JS files, fonts, and SVGs would live here.

`templates/` contains all of your Twig templates. These pretty much correspond 1 to 1 with the PHP files that respond to the WordPress template hierarchy. At the end of each PHP template, you'll notice a `Timber::render()` function whose first parameter is the Twig file where that data (or `$context`) will be used. Just an FYI.

`bin/` and `tests/` ... basically don't worry about (or remove) these unless you know what they are and want to.

## Other Resources

The [main Timber Wiki](https://github.com/jarednova/timber/wiki) is super great, so reference those often. Also, check out these articles and projects for more info:

* [This branch](https://github.com/laras126/timber-starter-theme/tree/tackle-box) of the starter theme has some more example code with ACF and a slightly different set up.
* [Twig for Timber Cheatsheet](http://notlaura.com/the-twig-for-timber-cheatsheet/)
* [Timber and Twig Reignited My Love for WordPress](https://css-tricks.com/timber-and-twig-reignited-my-love-for-wordpress/) on CSS-Tricks
* [A real live Timber theme](https://github.com/laras126/yuling-theme).
* [Timber Video Tutorials](http://timber.github.io/timber/#video-tutorials) and [an incomplete set of screencasts](https://www.youtube.com/playlist?list=PLuIlodXmVQ6pkqWyR6mtQ5gQZ6BrnuFx-) for building a Timber theme from scratch.

# Surface algos

- 5 Curated posts (weekly rotation)
- Trending - Facebook Likes + Actual Views + Tweets with links - Currently to be subbed by post click count


# Gigs scenne
- Are you a fan or a comic
- Comic goes to open mics
- Fan goes to Watch in bed or Get out
  - Watch in bed -> reviews
  - Get out -> Listing
    - Listing filterable by Location / Time / Comic Attributes (name, language, tag(adult, political, family, open-mic))
    - If deadant recommened - has "Dead And Recomended" stamp + link to review
    - Also has deadant related posts
    - Price as well 
    - if BMS has an API (say selling out fast)
    - Buy Link goes to BMS or Insider via referral links
  - Can get notifications via email / SMS

# weekly newsletter

# Ads


# EVENT PAGE
Image - landscape (see https://res.cloudinary.com/dwzmsvp7f/image/fetch/q_75,f_auto,w_800/https%3A%2F%2Fmedia.insider.in%2Fimage%2Fupload%2Fc_crop%2Cg_custom%2Fv1547105642%2Fuazp0tbfrs3yfga3drtf.jpg)
Metadata - Qualitative tags
Related Articles - 5 latest
About
Venue info - map / address / venue meta
Other events you might like - 10
Link out to artist page
Share - 
Terms & C - TBD

# ARTIST PAGE
- Image
- events
- Articles about artist
- About Artist

# VENUE PAGE
- Upcoming events
- Image
- About

#Event Main page
- Surprise me

### CONTENT
- Upload All Artists (if you have images / content - please add as well)
- Link all articles with Artists
- Articles need excepts
- Upload events and venues