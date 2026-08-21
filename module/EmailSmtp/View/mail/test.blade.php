@extends('module::Vendor.View.mail.frame')

@section('pageTitle','测试邮件')

@section('bodyContent')
    <p>如果您能收到这封邮件，说明您的邮件服务器配置正常。以下内容用于测试邮件模板样式渲染效果。</p>

    <h2>段落与文字</h2>
    <p>这是普通正文段落，用于展示基础阅读体验。邮件模板最大宽度为 <strong>800px</strong>，整体采用浅色系风格，字体大小为 15px，行高 1.8，确保在各类邮箱客户端中都有良好的可读性。</p>
    <p>这是第二段文字，包含一个 <a href="https://example.com">超链接示例</a>，鼠标悬停时会显示下划线。</p>

    <hr>

    <h2>标题层级</h2>
    <h1>一级标题（22px）</h1>
    <h2>二级标题（19px）</h2>
    <h3>三级标题（17px）</h3>
    <h4>四级标题（15px）</h4>

    <hr>

    <h2>列表样式</h2>
    <h3>无序列表</h3>
    <ul>
        <li>邮件最大宽度 800px</li>
        <li>卡片式圆角容器</li>
        <li>顶部 4px 品牌色腰线</li>
        <li>纯文字站点名称</li>
    </ul>
    <h3>有序列表</h3>
    <ol>
        <li>打开邮件模板</li>
        <li>检查样式渲染</li>
        <li>确认布局正常</li>
        <li>完成测试</li>
    </ol>

    <hr>

    <h2>引用块</h2>
    <blockquote>
        <p>这是一段引用文字，左侧带有品牌色竖线标识，背景为浅灰色，右侧带有圆角。多段落引用时，最后一段底部无多余间距。</p>
        <p>—— 第二段引用内容</p>
    </blockquote>

    <hr>

    <h2>代码样式</h2>
    <p>行内代码示例：请使用 <code>php artisan modstart:seed-test</code> 命令运行测试。</p>
    <p>代码块示例：</p>
    <pre>function greet($name) {
    return 'Hello, ' . $name . '!';
}

echo greet('World');
// 输出: Hello, World!</pre>

    <hr>

    <h2>辅助文字</h2>
    <p><small>这是一段 small 标签表示的辅助文字，字号 13px，颜色更浅。</small></p>
    <p><span class="text-muted">这是一段 .text-muted 样式的辅助文字，效果与 small 相同。</span></p>

    <hr>

    <h2>数据表格</h2>
    <table class="ub-email-table">
        <tr>
            <th>项目名称</th>
            <th>状态</th>
            <th>更新时间</th>
        </tr>
        <tr>
            <td>邮件模板设计</td>
            <td>已完成</td>
            <td>2026-07-07</td>
        </tr>
        <tr>
            <td>表格圆角修复</td>
            <td>已完成</td>
            <td>2026-07-07</td>
        </tr>
        <tr>
            <td>样式预定义</td>
            <td>已完成</td>
            <td>2026-07-07</td>
        </tr>
    </table>

    <p style="margin-top:24px;">以上为全部样式测试内容，如所有元素渲染正常，说明邮件模板配置无误。</p>
@endsection
