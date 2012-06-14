
App = (function($){
    //Extendable view
    jQueryView = Backbone.View.extend({
        initialize: function(){
            this.el = $(this.el);
            this.template = $(this.template);
        }
    });

    HomeView = jQueryView.extend({
        el: '#content',
        template: '#homepage-content',
        initialize: function(){
            jQueryView.prototype.initialize.call(this);
        },
        render: function(){
            this.el.html(this.template.tmpl());
           $('#subContent').html($('#hobbies-history-content').tmpl());
           if(isMobile){
               $('#Hobbies .accordion-body').each(function(){
                   $(this).removeClass('in').addClass('collapse');
               });
               $('#History .accordion-body').each(function(){
                   $(this).removeClass('in').addClass('collapse');
               });
           }
        }
    });
    
    ContactView = jQueryView.extend({
       el: '#content',
       template: '#contact-form-content',
       initialize: function(){
            jQueryView.prototype.initialize.call(this);
        },
        render: function(){
            this.el.html(this.template.tmpl());
           $('#subContent').html('');
        }
    });

    PostListView = jQueryView.extend({
        el: '#contentList',
        template: '#post-template',
        events: {
            'click #Posts': 'render'
        },
        initialize: function(){
            jQueryView.prototype.initialize.call(this);
        },
        render: function(){
            var self = this;
            console.log(self);
            console.log(self.collection);
            _.each(self.collection.models, function(post){
                post.attributes.timeago = $.timeago(post.attributes.iso8601);
            });
            self.el.html(self.template.tmpl(self.collection.toJSON()));
        }
    });

    RepoView = jQueryView.extend({
        el: '#content',
        template: '#repo-details-template',
        initialize: function(){
            jQueryView.prototype.initialize.call(this);
        },
        render: function(){
            console.log(this.model);
            this.el.html(this.template.tmpl(this.model.toJSON()));
        }
    });

    ProjectsDropDownView = jQueryView.extend({
        el: '#projectsDropDown',
        template: '#projects-dropdown-template',

        loadFromApi: function(){

        },

        initialize: function(){
            var self = this;
            jQueryView.prototype.initialize.call(this);
            this.render();
        },
        render: function(){
            this.el.html(this.template.tmpl(Repos.toJSON()));
            $('#sidebarList').html(this.template.tmpl(Repos.toJSON()));
            $('#projectsList').html(this.template.tmpl(Repos.toJSON()));
        }
    });

    UserListView = jQueryView.extend({
        el: '#content',
        template: '#user-template',

        initialize: function(){
            //super
            jQueryView.prototype.initialize.call(this);
            this.render();
        },

        render: function(){
            console.log('Users:');
            console.log(Users);
            this.el.html(this.template.tmpl(Users.toJSON()));
            $('#subContent').html('');
            this.el.append("<div class='clear-both'></div>");
        }
    });

    UserView = jQueryView.extend({
        el: '#subContent',
        template: '#user-details-template',

        initialize: function(){
            //super
            jQueryView.prototype.initialize.call(this);
            this.render();
        },

        render: function(){
            var self = this;			
            self.el.html(self.template.tmpl(self.model.toJSON()));	
        }
    });

    var self = {};
    self.start = function(){
        new AppRouter();
        Backbone.history.start();
        new ProjectsDropDownView();
        new PostListView({collection:Posts}).render();
    };
    return self;
});


//Essentially a main function (starts the app)
$(function(){
	
    });