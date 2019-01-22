<?php

namespace Tests\Unit\Tenants;

use App\Console\Kernel;
use App\Models\Order;
use App\Models\Log;
use App\Models\OrderTag;
use App\Models\Reply;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Config;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class OrderTest.
 *
 * @package Tests\Unit\Tenants
 */
class OrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Refresh the in-memory database.
     *
     * @return void
     */
    protected function refreshInMemoryDatabase()
    {
        $this->artisan('migrate',[
            '--path' => 'database/migrations/tenant'
        ]);

        $this->app[Kernel::class]->setArtisan(null);
    }

    /**
     * @test
     * @group orders
     */
    public function assign_user() {
        $order = Order::create([
            'subject' => 'Cable VGA per Aula 5',
            'description' => 'bla bla bla'
        ]);
        $this->assertNull($order->user);
        $result = $order->assignUser($user = factory(User::class)->create());
        $order = $order->fresh();
        $this->assertNotNull($order->user);
        $this->assertEquals($user->id,$order->user->id);
        $this->assertTrue($order->is($result));
    }

    /**
     * @test
     * @group orders
     */
    public function assign_user_by_id() {
        $order = Order::create([
            'subject' => 'No funciona PC aula 5',
            'description' => 'bla bla bla'
        ]);
        $this->assertNull($order->user);
        $user = factory(User::class)->create();
        $result = $order->assignUser($user->id);
        $order = $order->fresh();

        $this->assertNotNull($order->user);
        $this->assertEquals($user->id,$order->user->id);
        $this->assertTrue($order->is($result));
    }

    /**
     * @test
     * @group orders
     */
    public function throwns_exception_assigning_incorrect_user() {
        $order = Order::create([
            'subject' => 'No funciona PC aula 5',
            'description' => 'bla bla bla'
        ]);
        $this->assertNull($order->user);
        try {
            $order->assignUser('pepe');
        } catch (\InvalidArgumentException $e) {

        }
    }

    /**
     * @test
     * @group orders
     */
    public function map()
    {
        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardo@jeans.com'
        ]);

        $order = Order::create([
            'subject' => 'No funciona pc2 aula 15',
            'description' => 'bla bla bla',
        ])->assignUser($user);

        $mappedOrder = $order->map();

        $this->assertCount(0,$mappedOrder['changelog']);
        $this->assertEquals(1,$mappedOrder['id']);
        $this->assertEquals($user->id,$mappedOrder['user_id']);
        $this->assertEquals('Pepe Pardo Jeans',$mappedOrder['user_name']);
        $this->assertEquals('pepepardo@jeans.com',$mappedOrder['user_email']);
        $this->assertEquals('MX',$mappedOrder['user_hashid']);

        $this->assertEquals('No funciona pc2 aula 15',$mappedOrder['subject']);
        $this->assertEquals('bla bla bla',$mappedOrder['description']);

        $this->assertNotNull($mappedOrder['created_at']);
        $this->assertNotNull($mappedOrder['updated_at']);
        $this->assertNotNull($mappedOrder['created_at_timestamp']);
        $this->assertNotNull($mappedOrder['updated_at_timestamp']);
        $this->assertNotNull($mappedOrder['formatted_created_at']);
        $this->assertNotNull($mappedOrder['formatted_updated_at']);
        $this->assertNotNull($mappedOrder['formatted_created_at_diff']);
        $this->assertNotNull($mappedOrder['formatted_updated_at_diff']);

        $this->assertEquals('orders',$mappedOrder['api_uri']);

        $this->assertNull($mappedOrder['closed_at']);
        $this->assertNull($mappedOrder['formatted_closed_at']);
        $this->assertNull($mappedOrder['formatted_closed_at_diff']);
        $this->assertNull($mappedOrder['closed_at_timestamp']);

        $this->assertCount(0, $mappedOrder['comments']);

        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepe@pardojeans.com'
        ]);
        $comment = Reply::create([
            'body' => 'Si us plau podeu aportar més info',
            'user_id' => $user->id
        ]);
        $user2 = factory(User::class)->create([
            'name' => 'Carles Puigdemont',
            'email' => 'krls@republicacatalana.cat'
        ]);
        $comment2 = Reply::create([
            'body' => 'En concret no funciona bla bla bla',
            'user_id' => $user2->id
        ]);
        $comment3 = Reply::create([
            'body' => 'Ok! Solucionat',
            'user_id' => $user->id
        ]);
        $order->addComment($comment);
        $order->addComment($comment2);
        $order->addComment($comment3);

        $order= $order->fresh();
        $mappedOrder = $order->map();
        $this->assertCount(3, $mappedOrder['comments']);
        $this->assertEquals('Si us plau podeu aportar més info',$mappedOrder['comments'][0]['body']);
        $this->assertEquals(2,$mappedOrder['comments'][0]['user_id']);
        $this->assertEquals('Pepe Pardo Jeans',$mappedOrder['comments'][0]['user']->name);
        $this->assertEquals('pepe@pardojeans.com',$mappedOrder['comments'][0]['user']->email);
        $this->assertEquals('En concret no funciona bla bla bla',$mappedOrder['comments'][1]['body']);
        $this->assertEquals(3,$mappedOrder['comments'][1]['user']->id);
        $this->assertEquals('Carles Puigdemont',$mappedOrder['comments'][1]['user']->name);
        $this->assertEquals('krls@republicacatalana.cat',$mappedOrder['comments'][1]['user']->email);
        $this->assertEquals('Ok! Solucionat',$mappedOrder['comments'][2]['body']);
        $this->assertEquals(2,$mappedOrder['comments'][2]['user_id']);
        $this->assertEquals('Pepe Pardo Jeans',$mappedOrder['comments'][2]['user']->name);
        $this->assertEquals('pepe@pardojeans.com',$mappedOrder['comments'][2]['user']->email);

        Auth::login($user);
        $order->close();
        $order= $order->fresh();
        $mappedOrder = $order->map();
        $this->assertNotNull($mappedOrder['closed_at']);
        $this->assertNotNull($mappedOrder['formatted_closed_at']);
        $this->assertNotNull($mappedOrder['closed_at_timestamp']);
        $this->assertEquals($mappedOrder['closed_by'], $user->id);


        $this->assertEquals($mappedOrder['closer_id'], $user->id);
        $this->assertEquals($mappedOrder['closer_name'], $user->name);
        $this->assertEquals($mappedOrder['closer_email'], $user->email);
        $this->assertEquals($mappedOrder['closer_hashid'], $user->hash_id);

        // TAGS
        $tag1 = OrderTag::create([
            'value' => 'Tag1',
            'description' => 'Tag 1 bla bla bla',
            'color' => '#453423'
        ]);
        $tag2 = OrderTag::create([
            'value' => 'Tag2',
            'description' => 'Tag 2 bla bla bla',
            'color' => '#223423'
        ]);
        $tag3 = OrderTag::create([
            'value' => 'Tag3',
            'description' => 'Tag 3 bla bla bla',
            'color' => '#333423'
        ]);
        $order->addTag($tag1);
        $order->addTag($tag2);
        $order->addTag($tag3);

        $order= $order->fresh();
        $mappedOrder = $order->map();
        $this->assertCount(3, $mappedOrder['tags']);
        $this->assertEquals('Tag1',$mappedOrder['tags'][0]['value']);
        $this->assertEquals('Tag 1 bla bla bla',$mappedOrder['tags'][0]['description']);
        $this->assertEquals('#453423',$mappedOrder['tags'][0]['color']);

        $this->assertEquals('Tag2',$mappedOrder['tags'][1]['value']);
        $this->assertEquals('Tag 2 bla bla bla',$mappedOrder['tags'][1]['description']);
        $this->assertEquals('#223423',$mappedOrder['tags'][1]['color']);

        $this->assertEquals('Tag3',$mappedOrder['tags'][2]['value']);
        $this->assertEquals('Tag 3 bla bla bla',$mappedOrder['tags'][2]['description']);
        $this->assertEquals('#333423',$mappedOrder['tags'][2]['color']);

        // Changelog
        $log1 = Log::create([
            'text' => 'Ha creat la incidència ' . $order->link(),
            'time' => $order->created_at,
            'action_type' => 'store',
            'module_type' => 'Orders',
            'user_id' => $order->user->id,
            'loggable_id' => $order->id,
            'loggable_type' => Order::class,
            'old_loggable' => null,
            'new_loggable' => json_encode($order->map()),
            'icon' => 'add',
            'color' => 'success'
        ]);
        $log2 = Log::create([
            'text' => 'Ha tancat la incidència ' . $order->link(),
            'time' => $order->updated_at,
            'action_type' => 'close',
            'module_type' => 'Orders',
            'user_id' => $order->user->id,
            'loggable_id' => $order->id,
            'loggable_type' => Order::class,
            'old_loggable' => json_encode($order->map()),
            'new_loggable' => json_encode($order->map()),
            'new_value' => 'Tancada',
            'old_value' => 'Oberta',
            'icon' => 'lock',
            'color' => 'success'
        ]);
        $order= $order->fresh();
        $mappedOrder = $order->map();
        $this->assertNotNull($mappedOrder['changelog']);
        $this->assertCount(2,$mappedOrder['changelog']);
        $this->assertEquals($mappedOrder['changelog'][0]['id'], $log1->id);
        $this->assertEquals($mappedOrder['changelog'][1]['id'], $log2->id);
    }

    /**
     * @test
     */
    public function close()
    {
        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardo@jeans.com'
        ]);
        Auth::login($user);

        $order = Order::create([
            'subject' => 'No funciona pc2 aula 15',
            'description' => 'bla bla bla',
        ])->assignUser($user);

        $this->assertNull($order->closed_at);

        $order->close();

        $order = $order->fresh();
        $this->assertNotNull($order->closed_at);
        $this->assertEquals($order->closed_by,$user->id);
        $this->assertTrue($order->closer->is($user));
    }

    /**
     * @test
     */
    public function open()
    {
        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardo@jeans.com'
        ]);

        $order = Order::create([
            'subject' => 'No funciona pc2 aula 15',
            'description' => 'bla bla bla',
        ])->assignUser($user);
        Auth::login($user);
        $order->close();
        $this->assertTrue($order->closer->is($user));

        $order->open();

        $order = $order->fresh();
        $this->assertNull($order->closed_at);
        $this->assertNull($order->closed_by);
        $this->assertNull($order->closer);
    }

    /**
     * @test
     * @group orders
     */
    function can_get_formatted_created_at_date()
    {
        $order = Order::create([
            'subject' => 'No funciona pc2 aula 15',
            'description' => 'bla bla bla',
            'created_at' => Carbon::parse('2016-12-01 8:00pm')
        ]);

        $this->assertEquals('08:00:00PM 01-12-2016', $order->formatted_created_at);
    }

    /**
     * @test
     * @group orders
     */
    function can_get_formatted_closed_at_date()
    {
        $order = Order::create([
            'subject' => 'No funciona pc2 aula 15',
            'description' => 'bla bla bla',
            'closed_at' => Carbon::parse('2016-12-01 8:00pm')
        ]);

        $this->assertEquals('08:00:00PM 01-12-2016', $order->formatted_closed_at);
    }

    /**
     * @test
     * @group orders
     */
    function can_get_closed_at_timestamp()
    {
        $order = Order::create([
            'subject' => 'No funciona pc2 aula 15',
            'description' => 'bla bla bla',
            'closed_at' => Carbon::parse('2016-12-01 8:00pm')
        ]);

        $this->assertEquals('1480618800', $order->closed_at_timestamp);
    }

    /**
     * @test
     * @group orders
     */
    function can_get_api_uri()
    {
        $order = Order::create([
            'subject' => 'No funciona pc2 aula 15',
            'description' => 'bla bla bla',
        ]);

        $this->assertEquals('orders', $order->api_uri);
    }

    /**
     * @test
     */
    public function can_add_reply()
    {
        $order = Order::create([
            'subject' => 'No funciona pc2 aula 15',
            'description' => 'bla bla bla',
        ]);
        $user = factory(User::class)->create();
        $reply = Reply::create([
            'body' => 'Si us plau podeu detallar una mica més el problema?',
            'user_id' => $user->id
        ]);
        $this->assertCount(0,$order->replies);
        $order->addReply($reply);
        $order = $order->fresh();
        $this->assertCount(1,$order->replies);
        $this->assertTrue($order->replies->first()->is($reply));
        $this->assertEquals('Si us plau podeu detallar una mica més el problema?', $order->replies->first()->body);
    }

    /**
     * @test
     */
    public function addTag()
    {
        $order = Order::create([
            'subject' => 'No funciona pc2 aula 15',
            'description' => 'bla bla bla',
        ]);
        $tag = OrderTag::create([
            'value' => 'Tag1',
            'description' => 'Tag 1 bla bla bla',
            'color' => '#453423'
        ]);
        $this->assertCount(0,$order->tags);
        $order->addTag($tag);
        $order = $order->fresh();
        $this->assertCount(1,$order->tags);
        $this->assertTrue($order->tags[0]->is($tag));
    }

    /**
     * @test
     * @group orders
     */
    public function usersWithRoleOrders()
    {
        $this->assertCount(0, Order::userWithRoleOrders());
        $role = Role::firstOrCreate(['name' => 'Orders']);
        Config::set('auth.providers.users.model', User::class);
        $user1 = factory(User::class)->create();
        $user1->assignRole($role);
        $user2 = factory(User::class)->create();
        $user2->assignRole($role);
        $user3 = factory(User::class)->create();
        $user3->assignRole($role);
        $this->assertCount(3, $orderUsers = Order::userWithRoleOrders());
    }

    /**
     * @test
     * @group orders
     */
    public function usersWithRoleOrdersManager()
    {
        $this->assertCount(0, Order::userWithRoleOrdersManager());
        $role = Role::firstOrCreate(['name' => 'OrdersManager']);
        Config::set('auth.providers.users.model', User::class);
        $user1 = factory(User::class)->create();
        $user1->assignRole($role);
        $user2 = factory(User::class)->create();
        $user2->assignRole($role);
        $user3 = factory(User::class)->create();
        $user3->assignRole($role);
        $this->assertCount(3, $orderUsers = Order::userWithRoleOrdersManager());
    }

    /**
     * @test
     * @group orders
     */
    public function usersWithOrdersRoles()
    {
        $this->assertCount(0, Order::usersWithOrdersRoles());
        $role = Role::firstOrCreate(['name' => 'OrdersManager']);
        Config::set('auth.providers.users.model', User::class);
        $user1 = factory(User::class)->create();
        $user1->assignRole($role);
        $user2 = factory(User::class)->create();
        $user2->assignRole($role);
        $user3 = factory(User::class)->create();
        $user3->assignRole($role);
        $user4 = factory(User::class)->create();
        $role2 = Role::firstOrCreate(['name' => 'Orders']);
        $user4->assignRole($role2);
        $this->assertCount(4, $orderUsers = Order::usersWithOrdersRoles());
    }


}
