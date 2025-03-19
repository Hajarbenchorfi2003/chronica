<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\ArticleModel;

class Categories extends BaseController
{
    protected $categoryModel;
    protected $articleModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
        $this->articleModel = new ArticleModel();
    }

    public function index()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/')->with('error', 'Accès non autorisé');
        }

        $data = [
            'title' => 'Catégories',
            'categories' => $this->categoryModel->getCategoriesWithArticleCount()
        ];

        return view('admin/categories', $data);
    }

    public function view($slug)
    {
        $category = $this->categoryModel->getCategoryBySlug($slug);
        
        if (!$category) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Catégorie non trouvée');
        }

        $data = [
            'title' => $category['name'],
            'category' => $category,
            'articles' => $this->articleModel->getArticlesByCategory($category['id'])
        ];

        return view('categories/view', $data);
    }

    public function create()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/')->with('error', 'Accès non autorisé');
        }

        $data = [
            'title' => 'Nouvelle catégorie',
            'validation' => \Config\Services::validation()
        ];

        return view('admin/categories/create', $data);
    }

    public function store()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/')->with('error', 'Accès non autorisé');
        }

        // Validation des données
        $rules = [
            'name' => 'required|min_length[2]|max_length[100]|is_unique[categories.name]',
            'description' => 'permit_empty|max_length[255]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $categoryData = [
            'name' => $this->request->getPost('name'),
            'slug' => url_title($this->request->getPost('name'), '-', true),
            'description' => $this->request->getPost('description')
        ];

        $this->categoryModel->insert($categoryData);

        return redirect()->to('/admin/categories')->with('success', 'Catégorie créée avec succès');
    }

    public function edit($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/')->with('error', 'Accès non autorisé');
        }

        $category = $this->categoryModel->find($id);
        
        if (!$category) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Catégorie non trouvée');
        }

        $data = [
            'title' => 'Modifier la catégorie',
            'category' => $category,
            'validation' => \Config\Services::validation()
        ];

        return view('admin/categories/edit', $data);
    }

    public function update($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/')->with('error', 'Accès non autorisé');
        }

        $category = $this->categoryModel->find($id);
        
        if (!$category) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Catégorie non trouvée');
        }

        // Validation des données
        $rules = [
            'name' => "required|min_length[2]|max_length[100]|is_unique[categories.name,id,{$id}]",
            'description' => 'permit_empty|max_length[255]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $categoryData = [
            'name' => $this->request->getPost('name'),
            'slug' => url_title($this->request->getPost('name'), '-', true),
            'description' => $this->request->getPost('description')
        ];

        $this->categoryModel->update($id, $categoryData);

        return redirect()->to('/admin/categories')->with('success', 'Catégorie mise à jour avec succès');
    }

    public function delete($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/')->with('error', 'Accès non autorisé');
        }

        $category = $this->categoryModel->find($id);
        
        if (!$category) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Catégorie non trouvée');
        }

        // Vérifier si la catégorie contient des articles
        $articles = $this->articleModel->getArticlesByCategory($id);
        if (!empty($articles)) {
            return redirect()->to('/admin/categories')->with('error', 'Impossible de supprimer une catégorie contenant des articles');
        }

        $this->categoryModel->delete($id);

        return redirect()->to('/admin/categories')->with('success', 'Catégorie supprimée avec succès');
    }
} 