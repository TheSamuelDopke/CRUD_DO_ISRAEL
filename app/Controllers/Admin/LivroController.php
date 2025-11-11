<?php

namespace App\Controllers\Admin;

use App\Core\Csrf;
use App\Core\View;
use App\Repositories\EditoraRepository;
use App\Repositories\LivroRepository;
use App\Services\LivroService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LivroController
{
    private View $view;
    private LivroRepository $repo;
    private LivroService $service;
    private EditoraRepository $editoraRepo;

    public function __construct()
    {
        $this->view = new View();
        $this->repo = new LivroRepository();
        $this->service = new LivroService();
        $this->editoraRepo = new EditoraRepository();
    }

    public function index(Request $request): Response
    {
        $page = max(1, (int)$request->query->get('page', 1));
        $perPage = 5;
        $total = $this->repo->countAll();
        $livros = $this->repo->paginate($page, $perPage);
        $pages = (int)ceil($total / $perPage);
        $editoras = $this->editoraRepo->getArray();
        $html = $this->view->render('admin/livros/index', compact('livros', 'page', 'pages', 'editoras'));
        return new Response($html);
    }

    public function create(): Response
    {
        $editoras = $this->editoraRepo->findAll();
        $html = $this->view->render('admin/livros/create', ['csrf' => Csrf::token(), 'errors' => [], 'editoras' => $editoras]);
        return new Response($html);
    }

    public function store(Request $request): Response
    {
        if (!Csrf::validate($request->request->get('_csrf'))) return new Response('Token CSRF inválido', 419);
        $errors = $this->service->validate($request->request->all());
        if ($errors) {
            $editoras = $this->editoraRepo->findAll();
            $html = $this->view->render('admin/livros/create', ['csrf' => Csrf::token(), 'errors' => $errors, 'old' => $request->request->all(), 'editoras' => $editoras]);
            return new Response($html, 422);
        }
        $livro = $this->service->make($request->request->all());
        $id = $this->repo->create($livro);
        return new RedirectResponse('/admin/livros/show?id=' . $id);
    }

    public function show(Request $request): Response
    {
        $id = (int)$request->query->get('id', 0);
        $livro = $this->repo->find($id);
        if (!$livro) return new Response('Livro não encontrado', 404);

        $editoraRepo = new EditoraRepository();
        $editora = $editoraRepo->find($livro['editora_id']);
        $livro['editora_nome'] = $editora['nome'] ?? 'Desconhecida';
        
        $html = $this->view->render('admin/livros/show', ['livro' => $livro]);
        return new Response($html);
    }

    public function edit(Request $request): Response
    {
        $id = (int)$request->query->get('id', 0);
        $livro = $this->repo->find($id);
        $editoras = $this->editoraRepo->findAll();
        if (!$livro) return new Response('Livro não encontrado', 404);
        $html = $this->view->render('admin/livros/edit', ['livro' => $livro, 'csrf' => Csrf::token(), 'errors' => [], 'editoras' => $editoras]);
        return new Response($html);
    }

    public function update(Request $request): Response
    {
        if (!Csrf::validate($request->request->get('_csrf'))) return new Response('Token CSRF inválido', 419);
        $data = $request->request->all();
        $errors = $this->service->validate($data);
        if ($errors) {
            $editoras = $this->editoraRepo->findAll();
            $html = $this->view->render('admin/livros/edit', ['livro' => array_merge($this->repo->find((int)$data['id']), $data), 'csrf' => Csrf::token(), 'errors' => $errors, 'editoras' => $editoras]);
            return new Response($html, 422);
        }
        $livro = $this->service->make($data);
        if (!$livro->id) return new Response('ID inválido', 422);
        $this->repo->update($livro);
        return new RedirectResponse('/admin/livros/show?id=' . $livro->id);
    }

    public function delete(Request $request): Response
    {
        if (!Csrf::validate($request->request->get('_csrf'))) return new Response('Token CSRF inválido', 419);
        $id = (int)$request->request->get('id', 0);
        if ($id > 0) $this->repo->delete($id);
        return new RedirectResponse('/admin/livros');
    }
}
