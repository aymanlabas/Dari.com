import Link from "next/link";
import {
  MapPin,
  Phone,
  Mail,
  Facebook,
  Twitter,
  Instagram,
  ShoppingBag,
} from "lucide-react";

export function Footer() {
  return (
    <footer className="bg-gray-900 text-gray-300 mt-20">
      <div className="container mx-auto py-16 px-4">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          <div>
            <div className="flex items-center gap-2 mb-4">
              <div className="w-10 h-10 bg-green-600 rounded-xl flex items-center justify-center">
                <ShoppingBag className="w-6 h-6 text-white" />
              </div>
              <span className="text-2xl font-bold text-white">L9it</span>
            </div>
            <p className="text-sm text-gray-400 mb-4">
              L9it li kat9elleb 3lih — The modern Moroccan marketplace.
            </p>
            <div className="flex gap-3">
              <a href="#" className="w-9 h-9 rounded-lg bg-gray-800 hover:bg-green-600 flex items-center justify-center transition-colors">
                <Facebook className="w-4 h-4" />
              </a>
              <a href="#" className="w-9 h-9 rounded-lg bg-gray-800 hover:bg-green-600 flex items-center justify-center transition-colors">
                <Twitter className="w-4 h-4" />
              </a>
              <a href="#" className="w-9 h-9 rounded-lg bg-gray-800 hover:bg-green-600 flex items-center justify-center transition-colors">
                <Instagram className="w-4 h-4" />
              </a>
            </div>
          </div>

          <div>
            <h4 className="text-white font-semibold mb-4">Menu</h4>
            <ul className="space-y-2 text-sm">
              <li><Link href="/" className="hover:text-green-400 transition-colors">Accueil</Link></li>
              <li><Link href="/categories" className="hover:text-green-400 transition-colors">Catégories</Link></li>
              <li><Link href="/annonces/new" className="hover:text-green-400 transition-colors">Déposer une annonce</Link></li>
              <li><Link href="/favoris" className="hover:text-green-400 transition-colors">Favoris</Link></li>
              <li><Link href="/messages" className="hover:text-green-400 transition-colors">Messages</Link></li>
            </ul>
          </div>

          <div>
            <h4 className="text-white font-semibold mb-4">Support</h4>
            <ul className="space-y-2 text-sm">
              <li><Link href="/a-propos" className="hover:text-green-400 transition-colors">À propos</Link></li>
              <li><Link href="/contact" className="hover:text-green-400 transition-colors">Contact</Link></li>
              <li><Link href="/conditions" className="hover:text-green-400 transition-colors">Conditions d'utilisation</Link></li>
              <li><Link href="/privacy" className="hover:text-green-400 transition-colors">Politique de confidentialité</Link></li>
              <li><Link href="/aide" className="hover:text-green-400 transition-colors">Aide</Link></li>
            </ul>
          </div>

          <div>
            <h4 className="text-white font-semibold mb-4">Contact</h4>
            <div className="space-y-3 text-sm">
              <div className="flex items-center gap-2">
                <MapPin className="w-4 h-4 text-green-500" />
                <span>Casablanca, Maroc</span>
              </div>
              <div className="flex items-center gap-2">
                <Phone className="w-4 h-4 text-green-500" />
                <span>+212 5xxx xxxx</span>
              </div>
              <div className="flex items-center gap-2">
                <Mail className="w-4 h-4 text-green-500" />
                <span>contact@l9it.ma</span>
              </div>
            </div>
          </div>
        </div>

        <div className="border-t border-gray-800 mt-12 pt-8 text-center text-sm text-gray-500">
          © 2026 L9it. Tous droits réservés.
        </div>
      </div>
    </footer>
  );
}
