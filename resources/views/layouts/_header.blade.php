<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">

            {{--折叠按钮--}}
            <button type="button" class="navbar-toggle collapse" data-toggle="collapse"
                    data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            {{--商标图片--}}
            <a class="navbar-brand" href="{{ url('/') }}">
                LaraBBS
            </a>

        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            {{--左侧导航栏--}}
            <ul class="nav navbar-nav">

            </ul>
            {{--右侧导航栏--}}
            <ul class="nav navbar-nav navbar-right">
                <li><a href="#">登录</a></li>
                <li><a href="#">注册</a></li>
            </ul>
        </div>
    </div>
</nav>