var AppRouter = Backbone.Router.extend({
    routes: {
        ""                      : "homePage",
        "Home"                  : "postList",
        "Team"                  : "userList",
        "Project/:id/:name"     : "repoDetails",
        "TeamMember/:id/:name"  : "userDetails",
        "Contact"               : "contact"
    },

    homePage: function(){
        homeView = new HomeView().render();

    },

    postList: function() {
        console.log('Loading: posts');
        $('[href="#Home"]').parent().addClass("active");
        $('[href="#Team"]').parent().removeClass("active");
        $('[href="#Contact"]').parent().removeClass("active");
        homeView = new HomeView().render();
        postListView = new PostListView({
            collection: Posts
        }).render();
        projectsDropDownView = new ProjectsDropDownView().render();
    },

    userList: function(){
        console.log('Loading: users');
        console.log(location.href);
        //update nav for current page
        $('[href="#Home"]').parent().removeClass("active");
        $('[href="#Contact"]').parent().removeClass("active");
        $('[href="#Team"]').parent().addClass("active");
        userListView = new UserListView();
    },

    userDetails: function(id) {
        console.log('Loading: userDetails');
        var User = Users.get(id);

        console.log(User);
        userView = new UserView({
            model: User
        });
        userListView = new UserListView();
        userView.render();
    },

    repoDetails: function(id) {
        console.log('Loading: repoDetails');
        var Repo = Repos.get(id);
        var RepoPosts = new PostCollection;

        RepoPosts.url =  "/api/projectCommits/user/" + Repo.get('owner') + "/project/" + Repo.get('name');
        RepoPosts.fetch({
            success: function(){
                commitsView = new PostListView({
                    collection: RepoPosts
                }).render();
            }
        });

    },

    contact: function(){
        console.log("Loading: Contact");
        $('[href="#Home"]').parent().removeClass("active");
        $('[href="#Contact"]').parent().addClass("active");
        $('[href="#Team"]').parent().removeClass("active");
        contactView = new ContactView();
        contactView.render();
        var c = new contact();
    }


});