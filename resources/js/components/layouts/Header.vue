<template>
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
</template>
<script>
    import bus from '../../bus';
    export default {
        data() {
            return {
                testAccount:true,
                balance:false,
                readingData:{},
                reading:false,
                passWostData:{
                    oldPwd:undefined,
                    newPwd:undefined,
                    repetition:undefined,
                },
                pwdStatus:false,
                registerUser:{},
                userId:'',
                personalData:{},
                information:false,
                afficheList:[],
                noticeList:[],
                formationPop:false,
                formation:false,
                bellPop:false,
                visible:false,
                collapse: false,
                fullscreen: false,
                name: 'admin',
                afficheCount:0,
                messageCount:0,
                page:{
                    total: 0,
                    pageIndex:1,
                    pageSize:20
                },
                pageAffiche:{
                    total: 0,
                    pageIndex:1,
                    pageSize:20
                },
                rules: {
                    oldPwd: [
                        {required: true, message: '必填', trigger: 'blur'},
                    ],
                    newPwd: [
                        {required: true, message: '必填', trigger: 'blur'}
                    ],
                    repetition: [
                        {required: true, message: '必填', trigger: 'blur'}
                    ],
                }
            }
        },
        mounted(){
            this.getUnread()
            if(document.body.clientWidth < 1500){
                this.collapseChage()
            }
            this.getIsShow()
            let that=this
            bus.$on("change",function(val){
                that.afficheCount=val.afficheCount
                that.messageCount=val.messageCount
            })
        },
        computed:{
            username(){
                let user = JSON.parse(sessionStorage.getItem('user'));
                let realName =  user.realName ? user.realName : this.name;
                return `${realName} @ 网来科技`
            }
        },
        methods:{
            //提交修改密码
            async enterPwd(){
                if(this.passWostData.newPwd===this.passWostData.repetition){
                    let res=await this.$apiFetch('put','Admin/users/updatePwd',{
                        oldPwd:this.passWostData.oldPwd,
                        newPwd:this.passWostData.newPwd
                    })
                    if(res){
                        this.pwdStatus=false
                        this.passWostData={
                            oldPwd:undefined,
                            newPwd:undefined,
                            repetition:undefined,
                        }
                        this.$message.success('修改成功')
                    }
                }else{
                    this.$message.error('两次密码不一致');
                }

            },
            //阅读通知或公告操作
            async readNoyice(row){
                this.reading=true
                let res=await this.$apiFetch('get',`Admin/notices/readNotice/${row.id}`)
                if(res){
                    this.readingData=res
                    this.getMessage()
                    this.getAffiche()
                    this.getUnread()
                }
            },
            bellBadge(){
                this.formationPop=true
                this.getAffiche()
            },
            notice(){
                this.formation=true
                this.getMessage()
            },
            //获取通知列表
            async getAffiche(){
                let res=await this.$apiFetch('get','Admin/notices/getAffiche',{
                    page:this.pageAffiche.pageIndex,
                    pageSize: this.pageAffiche.pageSize,
                })
                if(res){
                    this.pageAffiche.total = res.count
                    this.afficheList=res.data
                }
            },
            //获取公告列表
            async getMessage(){
                let res=await this.$apiFetch('get','Admin/notices/getMessage',{
                    page:this.page.pageIndex,
                    pageSize: this.page.pageSize,
                })
                if(res){
                    this.page.total = res.count
                    this.noticeList=res.data
                }
            },
            // 用户名下拉菜单选择事件
            async handleCommand(command) {
                if(command == 'loginout'){
                    let res=await this.$apiFetch('post','Login/logout')
                    if(res){
                        sessionStorage.removeItem('user')
                        sessionStorage.removeItem('token')
                        this.$router.push('/login')
                    }
                }
                if(command =='personal'){
                    this.information=true
                    this.userId=JSON.parse(sessionStorage.getItem('user')).id
                    let res = await this.$apiFetch('get',`Admin/user/${this.userId}`)
                    if(res){
                        this.registerUser=res
                    }
                }
                if(command=='passWord'){
                    this.pwdStatus=true
                }
            },
            // 侧边栏折叠
            collapseChage(){
                this.collapse = !this.collapse;
                bus.$emit('collapse', this.collapse);
            },
            // 全屏事件
            handleFullScreen(){
                let element = document.documentElement;
                if (this.fullscreen) {
                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                    } else if (document.webkitCancelFullScreen) {
                        document.webkitCancelFullScreen();
                    } else if (document.mozCancelFullScreen) {
                        document.mozCancelFullScreen();
                    } else if (document.msExitFullscreen) {
                        document.msExitFullscreen();
                    }
                } else {
                    if (element.requestFullscreen) {
                        element.requestFullscreen();
                    } else if (element.webkitRequestFullScreen) {
                        element.webkitRequestFullScreen();
                    } else if (element.mozRequestFullScreen) {
                        element.mozRequestFullScreen();
                    } else if (element.msRequestFullscreen) {
                        // IE11
                        element.msRequestFullscreen();
                    }
                }
                this.fullscreen = !this.fullscreen;
            },
            //获取未读系统公告数量
            async getUnread(){
                let res = await this.$apiFetch('get','Admin/notices/getUnread')
                if(res){
                    this.afficheCount=res.afficheCount
                    this.messageCount=res.messageCount
                }
            },
            closeBtn(){
                this.formation=false
                this.formationPop=false
                this.pwdStatus=false
                this.passWostData={
                    oldPwd:undefined,
                      newPwd:undefined,
                      repetition:undefined,
                }
            },
            handleCurrentChangeUserPermission(val){
                this.page.pageIndex = val
                this.getMessage()
            },
            handleCurrentChangeAffiche(val){
                this.pageAffiche.pageIndex=val
                this.getAffiche()
            },
            //显示权限
            getIsShow(){
                let val=sessionStorage.getItem('user')
                let isShow=JSON.parse(val)
                if(isShow.isOwner==0){
                    this.balance=true
                }
                if(isShow.userName=='admin@100008'){
                    this.testAccount=false
                }
            }

        },
    }
</script>
<style>
    .el-table--small td{
        padding: 0px;
    }
    .el-dialog__header{
        background-color: #5278B1;
        padding: 10px 20px;
    }
    .el-dialog__body{
        padding-bottom: 0;
        overflow: hidden;
    }
    .el-upload--text{
        border: 0;
        width: 100px;
        height: 33px;
    }
    .lineQuery{
        display: inline-block;
        margin-bottom: 10px;
    }
</style>
<style scoped>
    .el-form-item--small{
        margin-bottom: 10px;
    }
    .btn-bell{
        margin: 0px 20px;
    }
    .header {
        position: relative;
        box-sizing: border-box;
        width: 100%;
        height: 50px;
        font-size: 22px;
        color: #fff;
    }
    .collapse-btn{
        float: left;
        padding: 0 21px;
        cursor: pointer;
        line-height: 50px;
    }
    .header .logo{
        float: left;
        width:250px;
        line-height: 50px;
    }
    .header-right{
        float: right;
        padding-right: 50px;
    }
    .header-user-con{
        display: flex;
        height: 50px;
        align-items: center;
    }
    .btn-fullscreen{
        transform: rotate(45deg);
        margin-right: 5px;
        font-size: 24px;
    }
    .btn-bell, .btn-fullscreen{
        position: relative;
        width: 30px;
        height: 30px;
        text-align: center;
        border-radius: 15px;
        cursor: pointer;
    }
    .badge{
        position: absolute;
        right: 0;
        top: -2px;
        width: 8px;
        height: 8px;
        border-radius: 4px;
        background: #f56c6c;
        color: #fff;
    }
    .btn-bell .el-icon-bell{
        color: #fff;
    }
    .user-name{
        margin-left: 10px;
    }
    .user-avator{
        margin-left: 20px;
    }
    .user-avator img{
        display: block;
        width:40px;
        height:40px;
        border-radius: 50%;
    }
    .el-dropdown-link{
        color: #fff;
        cursor: pointer;
    }
    .el-dropdown-menu__item{
        text-align: center;
    }
    .fa-envelope{
        font-size: 16px;
        color: #fff;
        margin-top: -10px;
    }
    .envelope{
        margin-right: -5px;
    }
    .popoverStyle{
        text-align: center;
        font-size: 12px;
        color: #aaa;
        position: relative;
    }
    .lineBox{
        width: 200px;
        height: 1px;
        background-color:#d9d9d9 ;
        position: absolute;
        top: 25px;
    }
    .usersList{
        width: 100%;
        height: 300px;
        margin-top: 12px;
        overflow: auto;
    }
    .linkman{
        box-sizing: border-box;
        width:100%;
        height: 35px;
        border: 1px solid #d9d9d9;
        font-weight: bold;
        line-height: 35px;
        padding-left: 15px;
        margin-bottom: 10px;
        background-color: #fff;
    }
    .el-icon-bell{
        font-size: 18px;
        margin-top: -10px;
    }
    .closeStyle{
        padding-left: 90%;
        margin-bottom: 20px;
    }
    .reading{
        color: #2d8cf0;
        cursor: pointer;
    }
    .userData{
        width: 100%;
        height: 300px;
        color: #000;
    }
    .ButtonBox{
        padding-bottom: 15px;
        float: right;
    }
    .infoColor{
        color: #3FC2FA;
    }
</style>
