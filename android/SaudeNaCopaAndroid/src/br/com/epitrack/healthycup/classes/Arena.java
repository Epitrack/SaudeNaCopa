package br.com.epitrack.healthycup.classes;

public class Arena {
	
	private int nome;
	private int imagem;
	private int cidade;
	private int descricao;
	
	public Arena(int nome, int img) {
		this.nome = nome;
		this.imagem = img;
	}
	
	public Arena(){
		
	}
	public int getNome() {		
		return nome;
	}
	public void setNome(int nome) {
		this.nome = nome;
	}
	public int getImagem() {
		return imagem;
	}
	public void setImagem(int imagem) {
		this.imagem = imagem;
	}
	public int getCidade() {
		return cidade;
	}
	public void setCidade(int cidade) {
		this.cidade = cidade;
	}
	public int getDescricao() {
		return descricao;
	}
	public void setDescricao(int descricao) {
		this.descricao = descricao;
	}	

}
