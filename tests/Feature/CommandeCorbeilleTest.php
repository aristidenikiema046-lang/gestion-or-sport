<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Commande;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommandeCorbeilleTest extends TestCase
{
    use RefreshDatabase;

    private function creerCommande(array $overrides = []): Commande
    {
        $client = Client::create([
            'nom' => 'Kouassi',
            'prenom' => 'Awa',
            'telephone' => '0700000000',
        ]);

        return Commande::create(array_merge([
            'reference' => Commande::prochaineReference(),
            'client_id' => $client->id,
            'type_article' => 'Maillots',
            'qualite' => 'F1',
            'modele' => 'Sublimation',
            'nom_equipe' => 'Equipe Test',
            'badge' => false,
            'quantite' => 1,
            'statut' => 'en_attente',
            'montant_total' => 10000,
            'avance_versee' => 0,
            'date_commande' => now(),
            'date_livraison_prevue' => now()->addDays(5),
        ], $overrides));
    }

    public function test_un_admin_peut_supprimer_une_commande_qui_disparait_de_la_liste_et_du_show(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $commande = $this->creerCommande();

        $response = $this->actingAs($admin)->delete(route('commandes.destroy', $commande));

        $response->assertRedirect(route('commandes.index'));
        $response->assertSessionHas('success');

        $this->assertSoftDeleted('commandes', ['id' => $commande->id]);

        $this->actingAs($admin)->get(route('commandes.index'))
            ->assertOk()
            ->assertDontSee($commande->reference);

        $this->actingAs($admin)->get(route('commandes.show', $commande))
            ->assertNotFound();
    }

    public function test_une_commande_supprimee_disparait_de_lhistorique_des_livraisons(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $commande = $this->creerCommande([
            'statut' => 'livree',
            'date_livraison_effective' => now(),
        ]);

        $this->actingAs($admin)->delete(route('commandes.destroy', $commande));

        $this->actingAs($admin)->get(route('commandes.livrees'))
            ->assertOk()
            ->assertDontSee($commande->reference);
    }

    public function test_une_commande_supprimee_apparait_dans_la_corbeille_avec_ses_infos(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $commande = $this->creerCommande();
        $commande->delete();

        $this->actingAs($admin)->get(route('commandes.corbeille'))
            ->assertOk()
            ->assertSee($commande->reference)
            ->assertSee($commande->client->nom_complet);
    }

    public function test_un_admin_peut_restaurer_une_commande_depuis_la_corbeille(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $commande = $this->creerCommande();
        $commande->delete();

        $response = $this->actingAs($admin)->patch(route('commandes.restaurer', $commande));

        $response->assertRedirect(route('commandes.corbeille'));
        $this->assertDatabaseHas('commandes', ['id' => $commande->id, 'deleted_at' => null]);

        $this->actingAs($admin)->get(route('commandes.index'))
            ->assertOk()
            ->assertSee($commande->reference);
    }

    public function test_un_admin_peut_supprimer_definitivement_une_commande_de_la_corbeille(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $commande = $this->creerCommande();
        $commande->delete();

        $response = $this->actingAs($admin)->delete(route('commandes.force-delete', $commande));

        $response->assertRedirect(route('commandes.corbeille'));
        $this->assertDatabaseMissing('commandes', ['id' => $commande->id]);
    }

    public function test_la_suppression_definitive_echoue_sur_une_commande_pas_dans_la_corbeille(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $commande = $this->creerCommande();

        $this->actingAs($admin)->delete(route('commandes.force-delete', $commande))
            ->assertNotFound();

        $this->assertDatabaseHas('commandes', ['id' => $commande->id]);
    }

    public function test_les_routes_de_corbeille_sont_refusees_a_un_utilisateur_non_admin(): void
    {
        $utilisateur = User::factory()->create(['role' => 'employe']);
        $commande = $this->creerCommande();
        $commande->delete();

        $this->actingAs($utilisateur)->get(route('commandes.corbeille'))->assertForbidden();
        $this->actingAs($utilisateur)->patch(route('commandes.restaurer', $commande))->assertForbidden();
        $this->actingAs($utilisateur)->delete(route('commandes.force-delete', $commande))->assertForbidden();

        $commandeActive = $this->creerCommande();
        $this->actingAs($utilisateur)->delete(route('commandes.destroy', $commandeActive))->assertForbidden();
    }

    public function test_les_routes_de_corbeille_sont_refusees_a_un_visiteur_non_authentifie(): void
    {
        $commande = $this->creerCommande();
        $commande->delete();

        $this->get(route('commandes.corbeille'))->assertRedirect(route('login'));
        $this->patch(route('commandes.restaurer', $commande))->assertRedirect(route('login'));
        $this->delete(route('commandes.force-delete', $commande))->assertRedirect(route('login'));
    }
}
