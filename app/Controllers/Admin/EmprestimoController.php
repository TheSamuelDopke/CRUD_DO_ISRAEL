<?php

namespace App\Controllers\Admin;

use App\Core\Csrf;
use App\Core\Flash;
use App\Core\View;
use App\Repositories\EmprestimoRepository;
use App\Services\EmprestimoService; 
use App\Repositories\LivroRepository;   
use App\Repositories\UserRepository;    
use App\Repositories\AutorRepository; 
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EmprestimoController
{
    private View $view;
    private EmprestimoRepository $repo;
    private EmprestimoService $service;
    private UserRepository $userRepo;    
    private LivroRepository $livroRepo;  
    private AutorRepository $autorRepo;  

    public function __construct()
    {
        $this->view = new View();
        $this->repo = new EmprestimoRepository();
        $this->service = new EmprestimoService();
        $this->userRepo = new UserRepository();   
        $this->livroRepo = new LivroRepository(); 
        $this->autorRepo = new AutorRepository(); 
    }

    public function index(Request $request): Response
    {
        $page = max(1, (int)$request->query->get('page', 1));
        $perPage = 5;
        $total = $this->repo->countAll();
        $emprestimos = $this->repo->paginate($page, $perPage); 
        $pages = (int)ceil($total / $perPage);
        $html = $this->view->render('admin/emprestimos/index', compact('emprestimos', 'page', 'pages')); 
        return new Response($html);
    }

    public function create(): Response
    {
        $users = $this->userRepo->findAll();    
        $livros = $this->livroRepo->findAll();  
        
        $html = $this->view->render('admin/emprestimos/create', [
            'csrf' => Csrf::token(), 
            'errors' => [], 
            'users' => $users,      
            'livros' => $livros     
        ]);
        return new Response($html);
    }

    public function store(Request $request): Response
    {
        if (!Csrf::validate($request->request->get('_csrf'))) return new Response('Token CSRF inválido', 419);
        
        $errors = $this->service->validate($request->request->all());
        
        if ($errors) {
            $users = $this->userRepo->findAll();    
            $livros = $this->livroRepo->findAll();  

            $html = $this->view->render('admin/emprestimos/create', [
                'csrf' => Csrf::token(), 
                'errors' => $errors, 
                'old' => $request->request->all(),
                'users' => $users,      
                'livros' => $livros     
            ]);
            return new Response($html, 422);
        }
        
        $emprestimo = $this->service->make($request->request->all());
        $id = $this->repo->create($emprestimo);
        
        return new RedirectResponse('/admin/emprestimos/show?id=' . $id);
    }

    public function show(Request $request): Response
    {
        $id = (int)$request->query->get('id', 0);
        $emprestimo = $this->repo->find($id); 
        
        if (!$emprestimo) return new Response('Empréstimo não encontrado', 404);
        
        $user = $this->userRepo->find($emprestimo->id_user);
        $livro = $this->livroRepo->find($emprestimo->id_livro);
        
        $autor = null;
        if ($livro && isset($livro['autor_id'])) {
            $autor = $this->autorRepo->find($livro['autor_id']);
        }

        $html = $this->view->render('admin/emprestimos/show', [
            'emprestimo' => $emprestimo,
            'user' => $user,     
            'livro' => $livro,   
            'autor' => $autor    
        ]); 
        return new Response($html);
    }

    public function edit(Request $request): Response
    {
        $id = (int)$request->query->get('id', 0);
        $emprestimo = $this->repo->find($id); 
        
        $users = $this->userRepo->findAll();    
        $livros = $this->livroRepo->findAll();  
        
        if (!$emprestimo) return new Response('Empréstimo não encontrado', 404);

        $html = $this->view->render('admin/emprestimos/edit', [
            'emprestimo' => $emprestimo, 
            'csrf' => Csrf::token(), 
            'errors' => [],
            'users' => $users,      
            'livros' => $livros     
        ]);
        return new Response($html);
    }

    

    public function update(Request $request): Response
    {
        if (!Csrf::validate($request->request->get('_csrf'))) return new Response('Token CSRF inválido', 419);
        
        $data = $request->request->all();
        $errors = $this->service->validate($data);
        
        if ($errors) {
            $users = $this->userRepo->findAll();    
            $livros = $this->livroRepo->findAll();  
            
            $html = $this->view->render('admin/emprestimos/edit', [
                'emprestimo' => array_merge((array)$this->repo->find((int)$data['id']), $data), 
                'csrf' => Csrf::token(), 
                'errors' => $errors,
                'users' => $users,      
                'livros' => $livros     
            ]);
            return new Response($html, 422);
        }
        
        $emprestimo = $this->service->make($data);
        if (!$emprestimo->id) return new Response('ID inválido', 422);
        
        $this->repo->update($emprestimo);
        
        return new RedirectResponse('/admin/emprestimos/show?id=' . $emprestimo->id);
    }

    public function delete(Request $request): Response
    {
        if (!Csrf::validate($request->request->get('_csrf'))) {
            return new Response('Token CSRF inválido', 419);
        }
        
        $id = (int)$request->request->get('id', 0);

        if ($id === 0) {
            Flash::push("danger", "ID do Empréstimo inválido.");
            return new RedirectResponse('/admin/emprestimos');
        }
        
        if ($id > 0) {
            $this->repo->delete($id);
            Flash::push("success", "Empréstimo excluído com sucesso!");
        }
        
        return new RedirectResponse('/admin/emprestimos');
    }
}