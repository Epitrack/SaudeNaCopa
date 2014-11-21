package br.com.epitrack.healthycup.classes;

import java.util.ArrayList;

import android.content.Context;
import android.content.Intent;
import android.location.Location;
import android.location.LocationManager;
import br.com.epitrack.healthycup.classes.factory.CategoriaFactory;

public class Usuario {
	private String userID;
	private String nome;
	private String sexo;
	private int pontos;
	private float engajamento;
	private int categoria;
	private int nivel;
	private String gcmID;
	private String idioma;
	private String email;
	private String senha;
	private int idade;
	private String device;
	private Categoria tipoCategoria;
	protected ArrayList<Integer> imagensSentimentos;
	private int arena;
	private String numHoraParaInformarSentimento;

	public ArrayList<Integer> getImagensSentimentos() {
		return imagensSentimentos;
	}

	public void setImagensSentimentos(ArrayList<Integer> imagensSentimentos) {
		this.imagensSentimentos = imagensSentimentos;
	}

	public Categoria getTipoCategoria() {
		return tipoCategoria;
	}

	public void setTipoCategoria(Categoria tipoCategoria) {
		this.tipoCategoria = tipoCategoria;
	}

	public Location getLocalizacao(Context context) {

		LocationManager Locationm = (LocationManager) context.getSystemService(Context.LOCATION_SERVICE);

		boolean network_enabled = Locationm.isProviderEnabled(LocationManager.NETWORK_PROVIDER);
		boolean gps_enabled = Locationm.isProviderEnabled(LocationManager.GPS_PROVIDER);

		if (!network_enabled && !gps_enabled) {
			context.startActivity(new Intent(android.provider.Settings.ACTION_LOCATION_SOURCE_SETTINGS));			
		}
		Location location = null;
		if (network_enabled){
			location = Locationm.getLastKnownLocation(LocationManager.NETWORK_PROVIDER);
		}else{
			if(gps_enabled){
				location = Locationm.getLastKnownLocation(LocationManager.GPS_PROVIDER);
			}
		}
		// String bestProvider = Locationm.getBestProvider(new Criteria(), true);

		// return Locationm.getLastKnownLocation(bestProvider);
		return location;
	}

	public String getEmail() {
		return email;
	}

	public void setEmail(String email) {
		this.email = email;
	}

	public String getSenha() {
		return senha;
	}

	public void setSenha(String senha) {
		this.senha = senha;
	}

	public String getUserID() {
		return userID;
	}

	public void setUserID(String userID) {
		this.userID = userID;
	}

	public String getNome() {
		return nome;
	}

	public void setNome(String nome) {
		this.nome = nome;
	}

	public String getSexo() {
		return sexo;
	}

	public void setSexo(String sexo) {
		this.sexo = sexo;
	}

	public int getPontos() {
		return pontos;
	}

	public void setPontos(int pontos) {
		this.pontos = pontos;
	}

	public float getEngajamento() {
		return engajamento;
	}

	public void setEngajamento(float engajamento) {
		this.engajamento = engajamento;
	}

	public int getCategoria() {
		return categoria;
	}

	public void setCategoria(int categoria) {
		this.categoria = categoria;
	}

	public int getNivel() {
		return nivel;
	}

	public void setNivel(int nivel) {
		this.nivel = nivel;
	}

	public String getGcmID() {
		return gcmID;
	}

	public void setGcmID(String gcmID) {
		this.gcmID = gcmID;
	}

	public String getIdioma() {
		return idioma;
	}

	public void setIdioma(String idioma) {
		this.idioma = idioma;
	}

	public int getIdade() {
		return idade;
	}

	public void setIdade(int idade) {
		this.idade = idade;
	}

	public String getDevice() {
		return device;
	}

	public void setDevice(String device) {
		this.device = device;
	}

	public void atualizaImagens() {
		// verifica qual categoria
		CategoriaFactory catFactory = new CategoriaFactory(this.categoria);
		this.tipoCategoria = catFactory.getCategoria();
		if (this.sexo.equals("masculino")) {
			this.setImagensSentimentos(this.tipoCategoria.getImagensSentimentosMasculino());
		} else {
			this.setImagensSentimentos(this.tipoCategoria.getImagensSentimentosFeminino());
		}

	}

	public int getArena() {
		return arena;
	}

	public void setArena(int arena) {
		this.arena = arena;
	}

	public String getNumHoraParaInformarSentimento() {
		return numHoraParaInformarSentimento;
	}

	public void setNumHoraParaInformarSentimento(String numHoraParaInformarSentimento) {
		this.numHoraParaInformarSentimento = numHoraParaInformarSentimento;
	}

}
