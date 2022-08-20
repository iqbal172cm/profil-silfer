<?php

namespace Tests\Feature;

use App\Data\Foo;
use App\Data\Bar;
use App\Data\Person;
use App\Services\HelloService;
use App\Services\HelloServiceIndonesia;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


class ServiceContainerTest extends TestCase
{

    public function testDependecy()
    {
        //  foo = new Foo();
        $foo1 = $this->app->make(Foo::class);
        $foo2 = $this->app->make(Foo::class);

        self::assertEquals("Foo", $foo1->foo());
        self::assertEquals("Foo", $foo2->foo());
        self::assertNotSame($foo1, $foo2);
    }

    public function testBind()
    {
        // $person = $this->app->make(Person::class);
        // self::assertNotNull($person);

        $this->app->bind(Person::class, function($app){
            return new Person("Muhammad", "Iqbal");
        });

        $person1 = $this->app->make(Person::class); // closure() // new Person("Muhammad", "Iqbal");
        $person2 = $this->app->make(Person::class); // closure() // new Person("Muhammad", "Iqbal");

        self::assertEquals('Muhammad', $person1->firstName);
        self::assertEquals('Iqbal', $person2->lastName);
        self::assertNotSame($person1, $person2);
    }

    public function testSingleton()
    {
        // $person = $this->app->make(Person::class);
        // self::assertNotNull($person);

        $this->app->singleton(Person::class, function($app){
            return new Person("Muhammad", "Iqbal");
        });

        $person1 = $this->app->make(Person::class); // new Person("Muhammad", "Iqbal"); if not exits
        $person2 = $this->app->make(Person::class); // return existing

        self::assertEquals('Muhammad', $person1->firstName);
        self::assertEquals('Muhammad', $person2->firstName);
        self::assertSame($person1, $person2);
    }

    public function testInstancen()
    {
        $person = new Person("Muhammad", "Iqbal");
        $this->app->instance(Person::class, $person);

        $person1 = $this->app->make(Person::class); // $person
        $person2 = $this->app->make(Person::class); // $person

        self::assertEquals('Muhammad', $person1->firstName);
        self::assertEquals('Muhammad', $person2->firstName);
        self::assertSame($person1, $person2);
    }

    public function testDependecyInjection()
    {
        $this->app->singleton(Foo::class, function ($app){
            return new Foo();
        });

        $foo = $this->app->make(Foo::class);
        $bar = $this->app->make(Bar::class);

        self::assertSame($foo, $bar->foo);
    }

    public function testDependecyInjectionClosure()
    {
        
        $this->app->singleton(Foo::class, function ($app){
            return new Foo();
        });
        $this->app->singleton(Bar::class, function ($app){
            $foo = $app->make(Foo::class);
            return new Bar($foo);
        });

        $foo = $this->app->make(Foo::class);
        $bar1 = $this->app->make(Bar::class);
        $bar2 = $this->app->make(Bar::class);

        self::assertSame($foo, $bar1->foo);
        self::assertSame($bar1, $bar2);
    }

    public function testInterfaceToClass()
    {
        // $this->app->singleton(HelloService::class, HelloServiceIndonesia::class);

        $this->app->singleton(HelloService::class, function ($app){
            return new HelloServiceIndonesia();
        });

        $helloService = $this->app->make(HelloService::class);

        self::assertEquals('Halo Iqbal', $helloService->hello('Iqbal'));
    }

}
