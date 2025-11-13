import axios from 'axios';

const api = axios.create({
  baseURL: process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8080/api',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

// Types
export interface Module {
  id: number;
  title: string;
  description: string;
  order: number;
  difficulty: 'beginner' | 'intermediate' | 'advanced';
  estimated_hours: number;
  learning_objectives: string[];
  is_active: boolean;
  lessons?: Lesson[];
}

export interface Lesson {
  id: number;
  module_id: number;
  title: string;
  description: string;
  content: string;
  type: 'theory' | 'practice' | 'quiz' | 'project';
  order: number;
  estimated_minutes: number;
  resources: Array<{ type: string; url: string; title: string }>;
  prerequisites: any[];
  is_active: boolean;
  exercises?: Exercise[];
}

export interface Exercise {
  id: number;
  lesson_id: number;
  title: string;
  description: string;
  instructions: string;
  starter_code?: string;
  solution_code?: string;
  test_cases: any[];
  points: number;
  difficulty: string;
}

export interface Quiz {
  id: number;
  module_id: number;
  title: string;
  description: string;
  passing_score: number;
  time_limit_minutes: number;
  randomize_questions: boolean;
  questions?: QuizQuestion[];
}

export interface QuizQuestion {
  id: number;
  quiz_id: number;
  type: 'multiple_choice' | 'true_false' | 'code';
  question: string;
  options: string[];
  correct_answer?: string;
  explanation?: string;
  points: number;
}

export interface LessonProgress {
  id: number;
  trainee_id: number;
  lesson_id: number;
  is_completed: boolean;
  time_spent_minutes: number;
  started_at?: string;
  completed_at?: string;
  notes: any;
}

export interface ModuleProgress {
  id: number;
  trainee_id: number;
  module_id: number;
  completion_percentage: number;
  lessons_completed: number;
  exercises_completed: number;
  quiz_passed: boolean;
  started_at?: string;
  completed_at?: string;
}

export interface TraineeStats {
  overall_progress: number;
  modules_completed: number;
  total_modules: number;
  lessons_completed: number;
  exercises_completed: number;
  quizzes_passed: number;
  total_time_spent: number;
  certificate_issued: boolean;
}

// API Methods
export const bootcampApi = {
  // Modules
  getModules: () => api.get<{ data: Module[] }>('/bootcamp/modules'),
  getModule: (id: number) => api.get<{ data: Module }>(`/bootcamp/modules/${id}`),
  getModuleProgress: (id: number, traineeId: number) =>
    api.get<{ data: ModuleProgress }>(`/bootcamp/modules/${id}/progress?trainee_id=${traineeId}`),

  // Lessons
  getLesson: (id: number) => api.get<{ data: Lesson }>(`/bootcamp/lessons/${id}`),
  startLesson: (id: number, traineeId: number) =>
    api.post<{ message: string; data: LessonProgress }>(`/bootcamp/lessons/${id}/start`, { trainee_id: traineeId }),
  completeLesson: (id: number, traineeId: number, timeSpent?: number) =>
    api.post<{ message: string; data: LessonProgress }>(`/bootcamp/lessons/${id}/complete`, {
      trainee_id: traineeId,
      time_spent_minutes: timeSpent,
    }),
  getLessonProgress: (id: number, traineeId: number) =>
    api.get<{ data: LessonProgress | null }>(`/bootcamp/lessons/${id}/progress?trainee_id=${traineeId}`),

  // Exercises
  getExercise: (id: number) => api.get<{ data: Exercise }>(`/bootcamp/exercises/${id}`),
  submitExercise: (id: number, traineeId: number, code: string) =>
    api.post<{ message: string; data: any }>(`/bootcamp/exercises/${id}/submit`, {
      trainee_id: traineeId,
      submitted_code: code,
    }),
  getExerciseSubmissions: (id: number, traineeId: number) =>
    api.get<{ data: any[] }>(`/bootcamp/exercises/${id}/submissions?trainee_id=${traineeId}`),

  // Quizzes
  getQuiz: (id: number) => api.get<{ data: Quiz }>(`/bootcamp/quizzes/${id}`),
  startQuiz: (id: number, traineeId: number) =>
    api.post<{ message: string; data: { attempt: any; questions: QuizQuestion[] } }>(
      `/bootcamp/quizzes/${id}/start`,
      { trainee_id: traineeId }
    ),
  submitQuiz: (id: number, traineeId: number, attemptId: number, answers: any[]) =>
    api.post<{ message: string; data: any }>(`/bootcamp/quizzes/${id}/submit`, {
      trainee_id: traineeId,
      attempt_id: attemptId,
      answers,
    }),
  getQuizAttempts: (id: number, traineeId: number) =>
    api.get<{ data: any[] }>(`/bootcamp/quizzes/${id}/attempts?trainee_id=${traineeId}`),

  // Dashboard
  getDashboard: (traineeId: number) =>
    api.get<{ trainee: any; stats: TraineeStats }>(`/trainees/${traineeId}/dashboard`),
};

export default api;
