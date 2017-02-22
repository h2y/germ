<?php get_header(); ?>

<div class="box archive-meta">
    <p class="title-meta">
        <i class="fa fa-exclamation-triangle"></i>
        欧糟了！一个未捕获的 404 异常被抛出
    </p>
</div>

<article class="entry-content page-404 box">
    <p>你所希望访问的地址并不存在，不过内容也许还没有删除，只是链接发生了改变。</p>
    <p>使用搜索功能通常可以让你找到需要的内容：</p>
    <form method="get" action="<?php echo get_home_url() ?>">
        <input type="text" placeholder="请输入关键字..." name="s" class="search">
    </form>
</article>


</div></div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
