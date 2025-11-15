'use client';

import { useEffect, useState } from 'react';
import { useRouter } from 'next/navigation';
import { useQuery } from '@tanstack/react-query';
import { bootcampApi, Module, TraineeStats } from '@/lib/api';
import { BookOpen, CheckCircle, Clock, Award, TrendingUp, Target } from 'lucide-react';
import Link from 'next/link';

export default function Dashboard() {
  const router = useRouter();
  const [traineeId, setTraineeId] = useState<string | null>(null);

  useEffect(() => {
    const id = localStorage.getItem('trainee_id');
    if (!id) {
      router.push('/');
    } else {
      setTraineeId(id);
    }
  }, [router]);

  const { data: modulesData, isLoading: modulesLoading } = useQuery({
    queryKey: ['modules'],
    queryFn: () => bootcampApi.getModules(),
    enabled: !!traineeId,
  });

  const { data: dashboardData, isLoading: dashboardLoading } = useQuery({
    queryKey: ['dashboard', traineeId],
    queryFn: () => bootcampApi.getDashboard(Number(traineeId)),
    enabled: !!traineeId,
  });

  if (!traineeId || modulesLoading || dashboardLoading) {
    return (
      <div className="min-h-screen flex items-center justify-center">
        <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600"></div>
      </div>
    );
  }

  const modules = modulesData?.data.data || [];
  const stats = dashboardData?.data.stats as TraineeStats;

  return (
    <div className="min-h-screen bg-gray-50">
      {/* Header */}
      <header className="bg-white shadow-sm">
        <div className="container mx-auto px-4 py-4">
          <div className="flex items-center justify-between">
            <h1 className="text-2xl font-bold text-gray-900">
              Laravel Bootcamp PRO
            </h1>
            <div className="flex items-center space-x-4">
              <span className="text-sm text-gray-600">
                Trainee ID: {traineeId}
              </span>
              <button
                onClick={() => {
                  localStorage.removeItem('trainee_id');
                  router.push('/');
                }}
                className="text-sm text-red-600 hover:text-red-700"
              >
                Salir
              </button>
            </div>
          </div>
        </div>
      </header>

      <main className="container mx-auto px-4 py-8">
        {/* Stats Overview */}
        <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <div className="bg-white rounded-lg shadow p-6">
            <div className="flex items-center justify-between mb-4">
              <div className="flex items-center justify-center w-12 h-12 bg-primary-100 rounded-lg">
                <TrendingUp className="w-6 h-6 text-primary-600" />
              </div>
              <span className="text-3xl font-bold text-primary-600">
                {stats.overall_progress}%
              </span>
            </div>
            <h3 className="text-sm font-medium text-gray-600">Progreso General</h3>
            <div className="mt-3 w-full bg-gray-200 rounded-full h-2">
              <div
                className="bg-primary-600 h-2 rounded-full transition-all"
                style={{ width: `${stats.overall_progress}%` }}
              />
            </div>
          </div>

          <div className="bg-white rounded-lg shadow p-6">
            <div className="flex items-center justify-between mb-4">
              <div className="flex items-center justify-center w-12 h-12 bg-green-100 rounded-lg">
                <CheckCircle className="w-6 h-6 text-green-600" />
              </div>
              <span className="text-3xl font-bold text-green-600">
                {stats.modules_completed}/{stats.total_modules}
              </span>
            </div>
            <h3 className="text-sm font-medium text-gray-600">Módulos Completados</h3>
          </div>

          <div className="bg-white rounded-lg shadow p-6">
            <div className="flex items-center justify-between mb-4">
              <div className="flex items-center justify-center w-12 h-12 bg-purple-100 rounded-lg">
                <Target className="w-6 h-6 text-purple-600" />
              </div>
              <span className="text-3xl font-bold text-purple-600">
                {stats.exercises_completed}
              </span>
            </div>
            <h3 className="text-sm font-medium text-gray-600">Ejercicios Resueltos</h3>
          </div>

          <div className="bg-white rounded-lg shadow p-6">
            <div className="flex items-center justify-between mb-4">
              <div className="flex items-center justify-center w-12 h-12 bg-yellow-100 rounded-lg">
                <Clock className="w-6 h-6 text-yellow-600" />
              </div>
              <span className="text-3xl font-bold text-yellow-600">
                {Math.round(stats.total_time_spent / 60)}h
              </span>
            </div>
            <h3 className="text-sm font-medium text-gray-600">Tiempo Invertido</h3>
          </div>
        </div>

        {/* Certificate Status */}
        {stats.certificate_issued && (
          <div className="bg-gradient-to-r from-yellow-400 to-yellow-600 rounded-lg shadow-lg p-6 mb-8 text-white">
            <div className="flex items-center">
              <Award className="w-12 h-12 mr-4" />
              <div>
                <h3 className="text-xl font-bold">¡Felicitaciones!</h3>
                <p className="text-yellow-100">Has completado el bootcamp y obtenido tu certificación</p>
              </div>
              <button className="ml-auto btn bg-white text-yellow-600 hover:bg-yellow-50">
                Descargar Certificado
              </button>
            </div>
          </div>
        )}

        {/* Modules List */}
        <div className="space-y-6">
          <h2 className="text-2xl font-bold text-gray-900">Módulos del Bootcamp</h2>

          {modules.map((module: Module) => (
            <Link
              key={module.id}
              href={`/modules/${module.id}`}
              className="block bg-white rounded-lg shadow hover:shadow-lg transition-shadow p-6"
            >
              <div className="flex items-start justify-between">
                <div className="flex-1">
                  <div className="flex items-center space-x-3 mb-2">
                    <span className="text-sm font-medium text-gray-500">
                      Módulo {module.order}
                    </span>
                    <span
                      className={`badge badge-${module.difficulty}`}
                    >
                      {module.difficulty}
                    </span>
                  </div>
                  <h3 className="text-xl font-bold text-gray-900 mb-2">
                    {module.title}
                  </h3>
                  <p className="text-gray-600 mb-4">{module.description}</p>

                  <div className="flex items-center space-x-6 text-sm text-gray-500">
                    <div className="flex items-center">
                      <Clock className="w-4 h-4 mr-1" />
                      {module.estimated_hours} horas
                    </div>
                    <div className="flex items-center">
                      <BookOpen className="w-4 h-4 mr-1" />
                      {module.lessons?.length || 0} lecciones
                    </div>
                  </div>

                  {module.learning_objectives && (
                    <div className="mt-4">
                      <p className="text-sm font-medium text-gray-700 mb-2">
                        Objetivos de aprendizaje:
                      </p>
                      <ul className="text-sm text-gray-600 space-y-1">
                        {module.learning_objectives.slice(0, 3).map((obj, idx) => (
                          <li key={idx} className="flex items-start">
                            <span className="text-primary-600 mr-2">•</span>
                            {obj}
                          </li>
                        ))}
                      </ul>
                    </div>
                  )}
                </div>

                <div className="ml-6 text-right">
                  <div className="w-24 h-24 relative">
                    <svg className="transform -rotate-90 w-24 h-24">
                      <circle
                        cx="48"
                        cy="48"
                        r="40"
                        stroke="currentColor"
                        strokeWidth="8"
                        fill="transparent"
                        className="text-gray-200"
                      />
                      <circle
                        cx="48"
                        cy="48"
                        r="40"
                        stroke="currentColor"
                        strokeWidth="8"
                        fill="transparent"
                        strokeDasharray={251.2}
                        strokeDashoffset={251.2 * (1 - 0 / 100)}
                        className="text-primary-600"
                      />
                    </svg>
                    <div className="absolute inset-0 flex items-center justify-center">
                      <span className="text-xl font-bold text-gray-900">0%</span>
                    </div>
                  </div>
                </div>
              </div>
            </Link>
          ))}
        </div>
      </main>
    </div>
  );
}
