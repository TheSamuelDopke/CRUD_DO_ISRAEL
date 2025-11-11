<?php

namespace App\Controllers\Admin;

use App\Core\Csrf;
use App\Core\Flash;
use App\Core\View;
use App\Repositories\EditoraRepository;
use App\Repositories\LivroRepository;
use App\Services\EditoraService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EditoraController
{
    private View $view;
    private EditoraRepository $repo;
    private EditoraService $service;

    private LivroRepository $productRepo;

    public function __construct()
    {
        $this->view = new View();
        $this->repo = new EditoraRepository();
        $this->service = new EditoraService();
        $this->productRepo = new LivroRepository();
    }

    public function index(Request $request): Response
    {
        $page = max(1, (int)$request->query->get('page', 1));
        $perPage = 5;
        $total = $this->repo->countAll();
        $editoras = $this->repo->paginate($page, $perPage);
        $pages = (int)ceil($total / $perPage);
        $html = $this->view->render('admin/editoras/index', compact('editoras', 'page', 'pages'));
        return new Response($html);
    }

    public function create(): Response
    {
        $html = $this->view->render('admin/editoras/create', ['csrf' => Csrf::token(), 'errors' => []]);
        return new Response($html);
    }

    public function store(Request $request): Response
    {
        if (!Csrf::validate($request->request->get('_csrf'))) return new Response('Token CSRF inválido', 419);
        $errors = $this->service->validate($request->request->all());
        if ($errors) {
            $html = $this->view->render('admin/editoras/create', ['csrf' => Csrf::token(), 'errors' => $errors, 'old' => $request->request->all()]);
            return new Response($html, 422);
        }
        $editora = $this->service->make($request->request->all());
        $id = $this->repo->create($editora);
        return new RedirectResponse('/admin/editoras/show?id=' . $id);
    }

    public function show(Request $request): Response
    {
        $id = (int)$request->query->get('id', 0);
        $editora = $this->repo->find($id);
        if (!$editora) return new Response('Editora não encontrada', 404);
        $html = $this->view->render('admin/editoras/show', ['editora' => $editora]);
        return new Response($html);
    }

    public function edit(Request $request): Response
    {
        $id = (int)$request->query->get('id', 0);
        $editora = $this->repo->find($id);
        if (!$editora) return new Response('Editora não encontrada', 404);
        $html = $this->view->render('admin/editoras/edit', ['editora' => $editora, 'csrf' => Csrf::token(), 'errors' => []]);
        return new Response($html);
    }

    public function update(Request $request): Response
    {
        if (!Csrf::validate($request->request->get('_csrf'))) return new Response('Token CSRF inválido', 419);
        $data = $request->request->all();
        $errors = $this->service->validate($data);
        if ($errors) {
            $html = $this->view->render('admin/editoras/edit', ['editora' => array_merge($this->repo->find((int)$data['id']), $data), 'csrf' => Csrf::token(), 'errors' => $errors]);
            return new Response($html, 422);
        }
        $editora = $this->service->make($data);
        if (!$editora->id) return new Response('ID inválido', 422);
        $this->repo->update($editora);
        return new RedirectResponse('/admin/editoras/show?id=' . $editora->id);
    }

    public function delete(Request $request): Response
    {
        // Pegar produto com categoria
        $categories = $this->productRepo->findByEditoraId((int)$request->request->get('id', 0));
        if (count($categories) > 0) {
            Flash::push("danger", "Categoria não pode ser excluída");
            return new RedirectResponse('/admin/categories');
        }

        if (!Csrf::validate($request->request->get('_csrf'))) return new Response('Token CSRF inválido', 419);
        $id = (int)$request->request->get('id', 0);
        if ($id > 0) $this->repo->delete($id);
        return new RedirectResponse('/admin/editoras');
    }
}
