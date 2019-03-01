window.deadant = window.deadant || {};
window.deadant.posts = {};

window.deadant.init = function(){
}

window.deadant.helpers = {

  setupArticleInlineLoader: () => {
    // Calculate the dimensions of the article modal
    var mainContainer = document.getElementById('posts-listing-container');
    var width = mainContainer.offsetWidth + mainContainer.offsetLeft;
  },

  searchBarClick: () => {
    $('.search-toggle').click(function() {
      $('body').toggleClass('searchbaropen');
      // if ($('body').hasClass('searchbaropen')) {
      //   $('.search-form').submit();
      // } else {
      // }
    })
  },

  injectSvgBehindPopularPosts: () => {
    var svghtml = '<svg class="backer" viewBox="0 0 442 462"><path fill="rgba(144, 19, 254, 1.00)" d="M0,263.418724 C0,127.63879 90.1216872,-42.8409038 218.156724,9.78228464 C346.191761,62.405473 440.21789,111.220928 440.21789,196.138153 C440.21789,281.055379 458.609028,391.885899 367.298754,446.87498 C275.988481,501.864061 6.05506545e-15,399.198658 0,263.418724 Z" id="Path-2"></path></svg>';
    $('.sidebar .wpp-list').parent().append(svghtml);
    console.log('inject html');
  },

  handlePreheaderItems: () => {
    $('.prenav-item').click(function() {
      $('.prenav-item').removeClass('active');
      $(this).addClass('active');
      $('.preheader-items').addClass('inactive');
      $($(this).attr('data-target')).removeClass('inactive');
    });
  },

  loadInlineArticle: () => {
    $('[data-inline-load] a').click(function(e) {
      e.preventDefault();
    })
    $('[data-inline-load]').click(function() {
      var id = $(this).attr('data-inline-load');
      var post = window.deadant.posts[id];
      var content = $('post-content-'+id);
      $('#article-inline-container').html(post.post_content);
      history.pushState({id:post.id, single: true, post: post}, post.title, post.slug);
      $('#article-inline').addClass('show');
    });
    window.onpopstate = function(event) {
      console.log('no history state',event);
      if ( event.state == null) {
        $('#article-inline').removeClass('show');
      } else {
        // console.log(event.state.post);
        console.log('has history state',event);
        $('#article-inline-container').html(event.state.post.post_content);
        $('#article-inline').addClass('show');
      }
    };
  },

  scrollInSideBar: () => {
    var sbholder = $('.sidebar-holder-post');
    var mt = (window.innerHeight - sbholder.height())/2;
    console.log(mt);
    if (window.innerWidth > 768) {
      sbholder.css({
        'width': $('aside.layout-sidebar').width() + 'px',
        'top': mt + 'px',
      });
    }
  }
}

window.deadant.postLoadInit = function(){
  // Handler when the DOM is fully loaded
  window.$ = window.jQuery;
  deadant.helpers.handlePreheaderItems();
  // deadant.helpers.loadInlineArticle();
  deadant.helpers.scrollInSideBar();
  deadant.helpers.searchBarClick();
  deadant.helpers.injectSvgBehindPopularPosts();
};


if (
    document.readyState === "complete" ||
    (document.readyState !== "loading" && !document.documentElement.doScroll)
) {
  window.deadant.postLoadInit();
} else {
  document.addEventListener("DOMContentLoaded", window.deadant.postLoadInit);
}

document.addEventListener('scroll', () => {
  console.log(window.scrollY);
  var scrollPercentage = Math.floor( (window.scrollY/(document.body.scrollHeight - window.innerHeight)) * 100 );
  document.documentElement.dataset.scroll = window.scrollY;
  document.documentElement.dataset.scrollPercentage = scrollPercentage;
  // document.documentElement.dataset.scroll25 = scrollPercentage > 9 && scrollPercentage < 80;
  document.documentElement.dataset.scroll25 = window.scrollY < document.body.scrollHeight - window.innerHeight - 600 && window.scrollY > 400;
});

window.deadant.init();
