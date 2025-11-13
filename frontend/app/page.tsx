'use client';

import { useEffect, useState } from 'react';
import { useRouter } from 'next/navigation';
import { bootcampApi } from '@/lib/api';
import { BookOpen, Award, Clock, TrendingUp } from 'lucide-react';

export default function Home() {
  const router = useRouter();
  const [traineeId, setTraineeId] = useState<string>('');
  const [isLoading, setIsLoading] = useState(false);

  useEffect(() => {
    // Check if trainee_id exists in localStorage
    const storedTraineeId = localStorage.getItem('trainee_id');
    if (storedTraineeId) {
      router.push('/dashboard');
    }
  }, [router]);

  const handleStart = () => {
    if (traineeId) {
      localStorage.setItem('trainee_id', traineeId);
      router.push('/dashboard');
    }
  };

  return (
    <main className="min-h-screen bg-gradient-to-br from-primary-50 to-blue-50">
      <div className="container mx-auto px-4 py-16">
        <div className="text-center mb-16">
          <h1 className="text-5xl font-bold text-gray-900 mb-4">
            Laravel Bootcamp PRO
          </h1>
          <p className="text-xl text-gray-600 max-w-2xl mx-auto">
            Aprende Laravel construyendo un ERP profesional desde cero.
            Curso completo con rigor científico y certificación oficial.
          </p>
        </div>

        <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-16">
          <div className="bg-white rounded-lg shadow-lg p-6">
            <div className="flex items-center justify-center w-12 h-12 bg-primary-100 rounded-lg mb-4">
              <BookOpen className="w-6 h-6 text-primary-600" />
            </div>
            <h3 className="text-lg font-semibold mb-2">7 Módulos</h3>
            <p className="text-gray-600 text-sm">
              Desde fundamentos hasta arquitectura avanzada
            </p>
          </div>

          <div className="bg-white rounded-lg shadow-lg p-6">
            <div className="flex items-center justify-center w-12 h-12 bg-green-100 rounded-lg mb-4">
              <Clock className="w-6 h-6 text-green-600" />
            </div>
            <h3 className="text-lg font-semibold mb-2">143 Horas</h3>
            <p className="text-gray-600 text-sm">
              Contenido estructurado y progresivo
            </p>
          </div>

          <div className="bg-white rounded-lg shadow-lg p-6">
            <div className="flex items-center justify-center w-12 h-12 bg-purple-100 rounded-lg mb-4">
              <TrendingUp className="w-6 h-6 text-purple-600" />
            </div>
            <h3 className="text-lg font-semibold mb-2">Proyecto Real</h3>
            <p className="text-gray-600 text-sm">
              Construye un ERP completo con 3 roles
            </p>
          </div>

          <div className="bg-white rounded-lg shadow-lg p-6">
            <div className="flex items-center justify-center w-12 h-12 bg-yellow-100 rounded-lg mb-4">
              <Award className="w-6 h-6 text-yellow-600" />
            </div>
            <h3 className="text-lg font-semibold mb-2">Certificación</h3>
            <p className="text-gray-600 text-sm">
              Certificado oficial al completar 100%
            </p>
          </div>
        </div>

        <div className="max-w-md mx-auto bg-white rounded-lg shadow-xl p-8">
          <h2 className="text-2xl font-bold text-gray-900 mb-6 text-center">
            Comienza tu Bootcamp
          </h2>

          <div className="space-y-4">
            <div>
              <label htmlFor="trainee_id" className="block text-sm font-medium text-gray-700 mb-2">
                Tu Trainee ID
              </label>
              <input
                type="text"
                id="trainee_id"
                value={traineeId}
                onChange={(e) => setTraineeId(e.target.value)}
                placeholder="Ingresa tu ID de trainee"
                className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
              />
              <p className="mt-2 text-sm text-gray-500">
                Si no tienes un ID, regístrate primero en el sistema principal
              </p>
            </div>

            <button
              onClick={handleStart}
              disabled={!traineeId || isLoading}
              className="w-full btn btn-primary py-3 text-lg disabled:opacity-50 disabled:cursor-not-allowed"
            >
              {isLoading ? 'Cargando...' : 'Comenzar Bootcamp'}
            </button>
          </div>

          <div className="mt-6 pt-6 border-t border-gray-200">
            <h3 className="font-semibold text-gray-900 mb-3">¿Qué aprenderás?</h3>
            <ul className="space-y-2 text-sm text-gray-600">
              <li className="flex items-start">
                <span className="text-primary-600 mr-2">✓</span>
                Laravel 11 y PHP 8.2+ moderno
              </li>
              <li className="flex items-start">
                <span className="text-primary-600 mr-2">✓</span>
                Arquitectura MVC y patrones de diseño
              </li>
              <li className="flex items-start">
                <span className="text-primary-600 mr-2">✓</span>
                Eloquent ORM y optimización de consultas
              </li>
              <li className="flex items-start">
                <span className="text-primary-600 mr-2">✓</span>
                APIs RESTful profesionales
              </li>
              <li className="flex items-start">
                <span className="text-primary-600 mr-2">✓</span>
                Testing automatizado (TDD)
              </li>
              <li className="flex items-start">
                <span className="text-primary-600 mr-2">✓</span>
                Performance y optimización avanzada
              </li>
            </ul>
          </div>
        </div>

        <div className="mt-16 text-center text-gray-600">
          <p className="text-sm">
            Bootcamp generado con rigor científico y metodología profesional
          </p>
        </div>
      </div>
    </main>
  );
}
