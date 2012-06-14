var User = Backbone.Model.extend({
  url : function(){
  	if(this.get("url") != null){
  		return this.get("url");
  	} else {
  		return "/api/user/username/" + this.get("login");
  	}
  },
  login: "default login",
  commits: new CommitCollection(),
  image: ""
});

var UserCollection = Backbone.Collection.extend({
    // Reference to this collection's model.
    model: User,
    url: "/api/users"
});