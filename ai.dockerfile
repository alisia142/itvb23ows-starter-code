FROM python:3.8

RUN apt-get update && apt-get clean

WORKDIR /app

RUN pip install Flask==3.0.0

RUN git clone https://github.com/hanze-hbo-ict/itvb23ows-hive-ai.git .

CMD flask --app app run --debug