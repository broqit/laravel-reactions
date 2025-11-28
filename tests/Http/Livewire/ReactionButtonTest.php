<?php

namespace Broqit\Laravel\Reactions\Tests\Http\Livewire;

use Broqit\Laravel\Reactions\Tests\TestCase;
use Broqit\Laravel\Reactions\Tests\Models\Post;
use Broqit\Laravel\Reactions\Tests\Models\Comment;
use Broqit\Laravel\Reactions\Http\Livewire\ReactionButton;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;

class ReactionButtonTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Create test tables
        Schema::create('posts', function ($table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->timestamps();
        });

        Schema::create('comments', function ($table) {
            $table->id();
            $table->text('content');
            $table->timestamps();
        });
    }

    #[Test]
    public function it_uses_default_reaction_types_when_no_model_specific_types_are_configured()
    {
        $post = Post::create([
            'title' => 'Test Post',
            'content' => 'Test Content',
        ]);

        $component = Livewire::test(ReactionButton::class, [
            'model' => $post,
        ]);

        $defaultTypes = config('reactions.types');
        $this->assertEquals($defaultTypes, $component->get('reactions'));
    }

    #[Test]
    public function it_uses_model_specific_reaction_types_when_configured()
    {
        // Configure model-specific types
        config([
            'reactions.model_types' => [
                Post::class => [
                    ['type' => 'like', 'name' => 'Like', 'icon' => '👍'],
                    ['type' => 'love', 'name' => 'Love', 'icon' => '❤️'],
                ],
            ],
        ]);

        $post = Post::create([
            'title' => 'Test Post',
            'content' => 'Test Content',
        ]);

        $component = Livewire::test(ReactionButton::class, [
            'model' => $post,
        ]);

        $reactions = $component->get('reactions');
        $this->assertCount(2, $reactions);
        $this->assertEquals('like', $reactions[0]['type']);
        $this->assertEquals('love', $reactions[1]['type']);
    }

    #[Test]
    public function it_uses_different_reaction_types_for_different_models()
    {
        // Configure different types for different models
        config([
            'reactions.model_types' => [
                Post::class => [
                    ['type' => 'like', 'name' => 'Like', 'icon' => '👍'],
                    ['type' => 'love', 'name' => 'Love', 'icon' => '❤️'],
                ],
                Comment::class => [
                    ['type' => 'thumbs_up', 'name' => 'Thumbs Up', 'icon' => '👍'],
                    ['type' => 'thumbs_down', 'name' => 'Thumbs Down', 'icon' => '👎'],
                ],
            ],
        ]);

        $post = Post::create([
            'title' => 'Test Post',
            'content' => 'Test Content',
        ]);

        $comment = Comment::create([
            'content' => 'Test Comment',
        ]);

        $postComponent = Livewire::test(ReactionButton::class, [
            'model' => $post,
        ]);

        $commentComponent = Livewire::test(ReactionButton::class, [
            'model' => $comment,
        ]);

        $postReactions = $postComponent->get('reactions');
        $commentReactions = $commentComponent->get('reactions');

        $this->assertCount(2, $postReactions);
        $this->assertEquals('like', $postReactions[0]['type']);
        $this->assertEquals('love', $postReactions[1]['type']);

        $this->assertCount(2, $commentReactions);
        $this->assertEquals('thumbs_up', $commentReactions[0]['type']);
        $this->assertEquals('thumbs_down', $commentReactions[1]['type']);
    }

    #[Test]
    public function it_falls_back_to_default_types_when_model_class_not_in_config()
    {
        // Configure types only for Post
        config([
            'reactions.model_types' => [
                Post::class => [
                    ['type' => 'like', 'name' => 'Like', 'icon' => '👍'],
                ],
            ],
        ]);

        $comment = Comment::create([
            'content' => 'Test Comment',
        ]);

        $component = Livewire::test(ReactionButton::class, [
            'model' => $comment,
        ]);

        $reactions = $component->get('reactions');
        $defaultTypes = config('reactions.types');
        $this->assertEquals($defaultTypes, $reactions);
    }

    #[Test]
    public function it_renders_with_correct_reaction_types()
    {
        // Configure model-specific types
        config([
            'reactions.model_types' => [
                Post::class => [
                    ['type' => 'like', 'name' => 'Like', 'icon' => '👍'],
                    ['type' => 'love', 'name' => 'Love', 'icon' => '❤️'],
                ],
            ],
        ]);

        $post = Post::create([
            'title' => 'Test Post',
            'content' => 'Test Content',
        ]);

        $component = Livewire::test(ReactionButton::class, [
            'model' => $post,
        ]);

        // Check that component has correct reactions property
        $reactions = $component->get('reactions');
        
        $this->assertIsArray($reactions);
        $this->assertCount(2, $reactions);
        $this->assertEquals('like', $reactions[0]['type']);
        $this->assertEquals('love', $reactions[1]['type']);
        
        // Check that the view renders with correct data
        $component->assertSee('Like');
        $component->assertSee('Love');
    }
}

