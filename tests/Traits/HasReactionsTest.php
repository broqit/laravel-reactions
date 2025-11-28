<?php

namespace Broqit\Laravel\Reactions\Tests\Traits;

use Broqit\Laravel\Reactions\Tests\TestCase;
use Broqit\Laravel\Reactions\Tests\Models\Post;
use Broqit\Laravel\Reactions\Tests\Models\Comment;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\Test;

class HasReactionsTest extends TestCase
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
    public function it_returns_default_reaction_types_when_no_model_specific_types_are_configured()
    {
        $post = Post::create([
            'title' => 'Test Post',
            'content' => 'Test Content',
        ]);

        $reactionTypes = $post->getReactionTypes();

        $this->assertIsArray($reactionTypes);
        $this->assertNotEmpty($reactionTypes);
        
        // Check that default types are returned
        $defaultTypes = config('reactions.types');
        $this->assertEquals($defaultTypes, $reactionTypes);
    }

    #[Test]
    public function it_returns_model_specific_reaction_types_when_configured()
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

        $reactionTypes = $post->getReactionTypes();

        $this->assertIsArray($reactionTypes);
        $this->assertCount(2, $reactionTypes);
        $this->assertEquals('like', $reactionTypes[0]['type']);
        $this->assertEquals('love', $reactionTypes[1]['type']);
    }

    #[Test]
    public function it_returns_different_reaction_types_for_different_models()
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

        $postReactionTypes = $post->getReactionTypes();
        $commentReactionTypes = $comment->getReactionTypes();

        $this->assertCount(2, $postReactionTypes);
        $this->assertEquals('like', $postReactionTypes[0]['type']);
        $this->assertEquals('love', $postReactionTypes[1]['type']);

        $this->assertCount(2, $commentReactionTypes);
        $this->assertEquals('thumbs_up', $commentReactionTypes[0]['type']);
        $this->assertEquals('thumbs_down', $commentReactionTypes[1]['type']);
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

        $reactionTypes = $comment->getReactionTypes();

        // Should fall back to default types
        $defaultTypes = config('reactions.types');
        $this->assertEquals($defaultTypes, $reactionTypes);
    }
}

