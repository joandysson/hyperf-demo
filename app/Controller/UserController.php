<?php declare(strict_types=1);

namespace App\Controller;

use App\Model\User;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Utils\Parallel;

class UserController extends AbstractController
{
    public function index(RequestInterface $request)
    {
        return User::get();
    }

    public function show(string $id)
    {
        return User::find($id);
    }

    public function store(RequestInterface $request)
    {
        return User::create($request->all());
    }

    public function delete(string $id)
    {
        return User::destroy($id);
    }

    public function storeWithWait(RequestInterface $request)
    {
        $parallel = new Parallel();

        foreach ($request->input('users') as $user) {
            $parallel->add(
                function () use ($user) {
                    User::create($user);
                },
                $user['id']
            );
        }

        return $parallel->wait();
    }
}
