var Post = Backbone.Model.extend({
  title: "default title",
  datetime: new Date(),
  source: "default source",
  image: "default image source",
  message: "default text for a post.message"
});

var PostCollection = Backbone.Collection.extend({
    // Reference to this collection's model.
    model: Post
});