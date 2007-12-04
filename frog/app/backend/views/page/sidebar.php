<?php if (Dispatcher::getAction() == 'index'): ?>
<!--
<p class="button"><a href="<?php echo get_url('page/add') ?>"><img src="images/page.png" align="middle" alt="page icon" /> <?php echo __('New Page') ?></a></p>
<p class="button"><a href="<?php echo get_url('layout/add') ?>"><img src="images/layout.png" align="middle" alt="layout icon" /> <?php echo __('New Layout') ?></a></p>
<p class="button"><a href="<?php echo get_url('snippet/add') ?>"><img src="images/snippet.png" align="middle" alt="snippet icon" /> <?php echo __('New Snippet') ?></a></p>

<div class="box">
    <h2><?php echo __('Statistics') ?></h2>
    <p>
        <b><?php echo Record::countFrom('Page'); ?></b> <?php echo __('pages') ?><br />
        <b><?php echo Record::countFrom('Comment'); ?></b> <?php echo __('comments') ?>
    </p>
    <table class="stats-table">
        <tr>
            <th>&nbsp;</th>
            <th class="header-visites">Visites</th>
            <th class="header-pageviews">Pageviews</th>
        </tr>
        <tr class="row-today">
            <th>Today</th>
            <td class="cell-visites"><?php echo Statistic::uniqueForToday() ?></td>
            <td class="cell-pageviews"><?php echo Statistic::viewForToday() ?></td>
        </tr>
        <tr class="row-yesterday">
            <th>Yesterday</th>
            <td class="cell-visites"><?php echo Statistic::uniqueForYesterday() ?></td>
            <td class="cell-pageviews"><?php echo Statistic::viewForYesterday() ?></td>
        </tr>
        <tr class="row-all-time">
            <th>All Time</th>
            <td class="cell-visites"><?php echo Statistic::unique() ?></td>
            <td class="cell-pageviews"><?php echo Statistic::view() ?></td>
        </tr>
    </table>
</div>
-->
<?php else: ?>

<?php if(isset($page)): ?>
<div class="box">
    <h2><?php echo __('Statistics') ?></h2>
    <table class="stats-table">
        <tr>
            <th>&nbsp;</th>
            <th class="header-visites">Visites</th>
            <th class="header-pageviews">Pageviews</th>
        </tr>
        <tr class="row-today">
            <th>Today</th>
            <td class="cell-visites"><?php echo Statistic::uniqueForToday($page->id) ?></td>
            <td class="cell-pageviews"><?php echo Statistic::viewForToday($page->id) ?></td>
        </tr>
        <tr class="row-yesterday">
            <th>Yesterday</th>
            <td class="cell-visites"><?php echo Statistic::uniqueForYesterday($page->id) ?></td>
            <td class="cell-pageviews"><?php echo Statistic::viewForYesterday($page->id) ?></td>
        </tr>
        <tr class="row-all-time">
            <th>All Time</th>
            <td class="cell-visites"><?php echo Statistic::unique($page->id) ?></td>
            <td class="cell-pageviews"><?php echo Statistic::view($page->id) ?></td>
        </tr>
    </table>
    <p>Unique visitor are only base on ip address.</p>
</div>
<?php endif; ?>

<div class="box">
    <h2><?php echo __('Quick tags list!') ?></h2>
    <p>
        <span class="this">$this-&gt;</span>id()<br />
        <span class="this">$this-&gt;</span>title()<br />
        <span class="this">$this-&gt;</span>breadcrumb()<br />
        <span class="this">$this-&gt;</span>author()<br />
        <span class="this">$this-&gt;</span>authorId()<br />
        <span class="this">$this-&gt;</span>updator()<br />
        <span class="this">$this-&gt;</span>updatorId()<br />
        <span class="this">$this-&gt;</span>slug()<br />
        <span class="this">$this-&gt;</span>url()<br />
        <span class="this">$this-&gt;</span>level()<br />
        
        <span class="this">$this-&gt;</span>link(<span class="optional">[label, option]</span>)<br />
        <span class="this">$this-&gt;</span>date(<span class="optional">[format]</span>)<br />
        <span class="this">$this-&gt;</span>breadcrumbs(<span class="optional">[separator]</span>)<br />
        <span class="this">$this-&gt;</span>hasContent(part)<br />
        <span class="this">$this-&gt;</span>content(<span class="optional">[part, inherit]</span>)<br />
        <span class="this">$this-&gt;</span>children(<span class="optional">[arguments]</span>)<br />
        
        <span class="this">$this-&gt;</span>find(slug)<br />
        <span class="this">$this-&gt;</span>parent()<br />
        
        <span class="this">$this-&gt;</span>includeSnippet(name)<br />
    </p>
</div>

<?php endif; ?>