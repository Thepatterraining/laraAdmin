<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        {{-- <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav> --}}

        <div class="header">
            <!--         折叠按钮-->
                    <div class="collapse-btn" @click="collapseChage">
                        <i class="el-icon-menu"></i>
                    </div>
                    <div class="logo"> 后台管理系统</div>

                    <div class="header-right">
                        <div class="header-user-con">
                            <!-- 用户名下拉菜单 -->
                            <el-dropdown class="user-name" trigger="click" @command="handleCommand">
                                <span class="el-dropdown-link">
                                    {{username}} <i class="el-icon-caret-bottom"></i>
                                </span>
                                <el-dropdown-menu slot="dropdown">
                                    <!-- <a href="http://blog.gdfengshuo.com/about/" target="_blank"> -->
                                        <el-dropdown-item command="passWord" v-show="testAccount">修改密码</el-dropdown-item>
                                    <!-- </a> -->
                                    <!-- <a href="https://github.com/lin-xin/vue-manage-system" target="_blank"> -->
                                        <el-dropdown-item  command="personal">个人信息</el-dropdown-item>
                                    <!-- </a> -->
                                    <el-dropdown-item divided  command="loginout">退出登录</el-dropdown-item>
                                </el-dropdown-menu>
                            </el-dropdown>

                            <!-- 公告中心 -->
                            <div class="btn-bell envelope" >
                                <el-popover placement="top" width="200" v-model="visible">
                                   <div class="popoverStyle">
                                       <p>您有{{this.messageCount}}条未读公告</p>
                                       <div class="lineBox"></div>
                                       <div style="margin-top: 20px;cursor: pointer" @click="notice">
                                           <span><i class="fa fa-envelope" style="font-size: 12px;color: #676a6c;margin-right: 7px"></i></span>
                                           查看所有
                                       </div>
                                   </div>
                                    <el-button type="text" slot="reference">
                                        <i class="fa fa-envelope"></i>
                                    </el-button>
                                </el-popover>
                                <span class="badge" v-if="messageCount"></span>
                            </div>
                            <!-- 消息通知中心 -->
                            <div class="btn-bell">

                                <el-popover placement="top" width="200" v-model="bellPop">
                                    <div class="popoverStyle">
                                        <p>您有{{this.afficheCount}}条未读通知</p>
                                        <div class="lineBox"></div>
                                        <div style="margin-top: 20px;cursor: pointer" @click="bellBadge">
                                            <span><i class="el-icon-message-solid" style="font-size: 12px;margin-right: 7px"></i></span>
                                            查看所有
                                        </div>
                                    </div>
                                    <el-button type="text" slot="reference">
                                        <i class="el-icon-bell" ></i>
                                    </el-button>
                                </el-popover>
                                <span class="badge" v-if="afficheCount"></span>
                            </div>
                            <!-- 全屏显示 -->
                            <div class="btn-fullscreen" @click="handleFullScreen">
                                <el-tooltip effect="dark" :content="fullscreen?`取消全屏`:`全屏`" placement="bottom">
                                    <i class="el-icon-rank"></i>
                                </el-tooltip>
                            </div>
                            <!-- 用户头像 -->
                            <!-- <div class="user-avator"><img src="../../assets/img/img.jpg"></div> -->

                        </div>
                    </div>
                    <!-- 查看公告-->
                    <el-dialog title="公告" v-dialogDrag :visible.sync="formation" width="760px" top="80px" :close-on-click-modal="false">
                        <div class="package">
                            <div class="linkman">公告</div>
                            <div class="usersList">
                                <el-table :data="noticeList" height="250" border style="width: 100%" highlight-current-row >
                                    <el-table-column prop="title" align="center" label="消息标题 " width="250" show-overflow-tooltip>
                                    </el-table-column>
                                    <el-table-column prop="createdAt" align="center" label="时间" width="180">
                                    </el-table-column>
                                    <el-table-column prop="status" align="center" label="状态" width="90">
                                    </el-table-column>
                                    <el-table-column prop="date" label="操作" align="center" width="100" >
                                        <template slot-scope="scope" >
                                            <span class="reading" @click="readNoyice(scope.row)" >阅读</span>
                                        </template>
                                    </el-table-column>
                                </el-table>
                                <!-- 分页器-->
                                <div >
                                    <el-pagination
                                      background
                                      @current-change="handleCurrentChangeUserPermission"
                                      :current-page="page.pageIndex"
                                      :page-size="page.pageSize"
                                      layout="total, prev, pager, next"
                                      :total="page.total">
                                    </el-pagination>
                                </div>
                            </div>
                        </div>
                        <div class="closeStyle">
                            <el-button type="info" plain @click="closeBtn">关闭</el-button>
                        </div>
                    </el-dialog>
                    <!-- 查看通知-->
                    <el-dialog title="通知" v-dialogDrag :visible.sync="formationPop" width="760px" top="80px" :close-on-click-modal="false">
                        <div class="package">
                            <div class="linkman">通知</div>
                            <div class="usersList">
                                <el-table :data="afficheList" height="250" border style="width: 100%" highlight-current-row >
                                    <el-table-column prop="title" align="center" label="通知" width="320" show-overflow-tooltip>
                                    </el-table-column>
                                    <el-table-column prop="createdAt" align="center" label="时间" width="180">
                                    </el-table-column>
                                    <el-table-column prop="status" align="center" label="状态" width="90">
                                    </el-table-column>
                                    <el-table-column prop="date" label="操作" align="center" width="100" >
                                        <template slot-scope="scope" >
                                            <span class="reading" @click="readNoyice(scope.row)" >阅读</span>
                                        </template>
                                    </el-table-column>
                                </el-table>
                                <!-- 分页器-->
                                <div >
                                    <el-pagination
                                      background
                                      @current-change="handleCurrentChangeAffiche"
                                      :current-page="pageAffiche.pageIndex"
                                      :page-size="pageAffiche.pageSize"
                                      layout="total, prev, pager, next"
                                      :total="pageAffiche.total">
                                    </el-pagination>
                                </div>
                            </div>
                        </div>
                        <div class="closeStyle">
                            <el-button type="info" plain @click="closeBtn">关闭</el-button>
                        </div>
                    </el-dialog>

                    <!-- 阅读公告-->
                    <el-dialog title="阅读" v-dialogDrag :visible.sync="reading" width="700px" top="180px" :close-on-click-modal="false">
                        <div >
                            <el-form ref="form" :model="readingData" label-width="100px">
                                <el-form-item label="标题: ">
                                    <el-input v-model="readingData.title"  style="width: 160px" ></el-input>
                                </el-form-item>
                                <el-form-item label="公告内容: ">
                                    <el-input type="textarea" :rows="13" v-model="readingData.content" >
                                    </el-input>
                                </el-form-item>
                            </el-form>
                        </div>
                        <div class="closeStyle">
                            <el-button type="info" plain @click="reading=false">关闭</el-button>
                        </div>
                    </el-dialog>

                    <!-- 查看个人信息-->
                    <el-dialog title="个人信息" v-dialogDrag :visible.sync="information" width="700px" top="80px" :close-on-click-modal="false">
                        <div class="userData" style="height: 300px">
                            <el-form ref="form" :model="registerUser" label-width="150px">
                                <el-form-item label="用户账号：">
                                    <span class="infoColor">{{registerUser.userName}}</span>
                                </el-form-item>
                                <el-form-item label="用户真实姓名：">
                                    <span class="infoColor">{{registerUser.realName}}</span>
                                </el-form-item>
                                <el-form-item label="用户角色：">
                                    <span v-for="item in registerUser.roleName" >
                                        <span class="infoColor">{{item.roleName}}</span>&nbsp;
                                    </span>
                                </el-form-item>
                                <el-form-item label="手机：">
                                    <span class="infoColor">{{registerUser.mobile}}</span>
                                </el-form-item>
                                <el-form-item label="所属部门：">
                                    <span class="infoColor">{{registerUser.departName}}</span>
                                </el-form-item>
                                <el-form-item label="账户余额：" v-if="balance">
                                    <span class="infoColor">{{registerUser.balanceAmount}}元</span>
                                </el-form-item>
                                <el-form-item label="E-Mail： ">
                                    <span class="infoColor">{{registerUser.email}}</span>
                                </el-form-item>
                            </el-form>
                        </div>
                        <div class="closeStyle">
                            <el-button type="info" plain @click="information=false">关闭</el-button>
                        </div>
                    </el-dialog>
                    <!-- 修改密码-->
                    <el-dialog title="修改密码" v-dialogDrag :visible.sync="pwdStatus" width="600px" top="80px" :close-on-click-modal="false">
                        <div class="userData" style="height: 200px">
                            <el-form ref="form" :model="passWostData" label-width="150px" :rules="rules">
                                <el-form-item label="原密码: " prop="oldPwd">
                                    <el-input v-model="passWostData.oldPwd" style="width: 180px" placeholder="请输入原密码"></el-input>
                                </el-form-item>
                                <el-form-item label="新密码: " prop="newPwd">
                                    <el-input v-model="passWostData.newPwd" style="width: 180px" placeholder="请输入新密码" show-password></el-input>
                                </el-form-item>
                                <el-form-item label="重复密码: " prop="repetition">
                                    <el-input v-model="passWostData.repetition" style="width: 180px" placeholder="请重复新密码" show-password></el-input>
                                </el-form-item>
                            </el-form>
                        </div>
                        <div class="ButtonBox">
                            <el-button @click="enterPwd" type="primary">提交</el-button>
                            <el-button type="info" plain @click="closeBtn">关闭</el-button>
                        </div>
                    </el-dialog>
                </div>
        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
