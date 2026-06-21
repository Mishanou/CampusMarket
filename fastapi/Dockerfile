FROM python:3.11-slim

WORKDIR /app

# Устанавливаем gcc (может потребоваться для сборки некоторых python-пакетов)
RUN apt-get update && apt-get install -y \
    gcc \
    && rm -rf /var/lib/apt/lists/*

# Сначала копируем только requirements.txt (для кэширования слоев Docker)
COPY requirements.txt .

RUN pip install --no-cache-dir --upgrade pip \
    && pip install --no-cache-dir -r requirements.txt

# Копируем весь остальной код микросервиса
COPY . .

# Открываем порт 8000, на котором будет работать FastAPI
EXPOSE 8000

# Команда для запуска uvicorn сервера
CMD ["uvicorn", "main:app", "--host", "0.0.0.0", "--port", "8000"]