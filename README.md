# Engine - PHP Workflow library

Engine is heavily inspired by symfony/workflow, but meant to be 
significantly simpler to use, and allow extra checks
about both the actor and the object of the workflow 
transitions and actions.

##Installation

You can install the library using composer

```shell script
composer require iconic/engine
```

##Quick Guide

All you have to do is create an instance of Engine and 
start defining and checking allowed actions on properties and from actors (supports getters/setters and public properties).

- Allow everyone to view
```php
$engine = new \Iconic\Engine\Engine();
$engine->allow('view');
$allowed = $engine->can('view'); //returns true
$allowed = $engine->can('edit'); //returns false
```

- Allow publishing of posts only if their status is draft 

```php
$post = new Post();
$post->status = "submitted";
$engine = new \Iconic\Engine\Engine();

$engine->allow('publish')->of('status', 'draft', 'published');

$allowed = $engine->can('publish', $post); //returns false
$engine->apply('publish', $post); //throws Exception

$post->status = "draft";
$allowed = $engine->can('publish', $post); //returns true
$engine->apply('publish', $post); //changes post status to "published"
```

- Allow only actors with "role" "editor" to edit, without defining object restrictions 

```php
$user = new User();
$user->role = "user";

$engine = new \Iconic\Engine\Engine();
$engine->allow('edit')->if('role', 'editor');
$allowed = $engine->can('edit', null, $user); //returns false
```

- Combine the two above scenarios. Allow posts with "status" "draft"
to be "published" by actors with "role" "editor"

```php
$editor = new User();
$editor->role = 'editor';
$user = new User();
$user->role = 'user';
$draftPost = new Post();
$draftPost->status = "draft";
$deletedPost = new Post();
$deletedPost->status = "deleted";

$engine = new \Iconic\Engine\Engine();
$engine->allow('publish')->of('status', 'draft', 'published');
$engine->allow('publish')->if('role', 'editor');

$engine->can('publish', $draftPost, $editor); //returns true
$engine->apply('publish', $draftPost, $editor); //changes post status to "published"

$engine->can('publish', $deletedPost, $editor); //returns false
$engine->apply('publish', $deletedPost, $editor); //throws exception
$engine->can('publish', $draftPost, $user); //returns false  
$engine->apply('publish', $draftPost, $user); //throws exception  
```

- You can define multiple rules for each action
