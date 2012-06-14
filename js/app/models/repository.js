var Repo = Backbone.Model.extend();

var RepoCollection = Backbone.Collection.extend({
    // Reference to this collection's model.
    model: Repo,
    url: "/api/projects"
});